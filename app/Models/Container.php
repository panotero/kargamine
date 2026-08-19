<?php

namespace App\Models;

use App\Exceptions\ProtectedRecordException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    protected $fillable = ['code', 'name', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
    ];

    public function type()
    {
        return $this->belongsTo(ContainerType::class, 'container_type_id');
    }

    public function classes()
    {
        return $this->hasMany(ContainerClass::class);
    }

    public function sizes()
    {
        return $this->hasMany(ContainerSize::class);
    }

    public function variants()
    {
        return $this->hasMany(ContainerVariant::class);
    }

    /**
     * Reconcile this container's Classes, Sizes and generated Variants with
     * the given entries - each entry updates the matching existing row in
     * place when it carries that row's id (a rename, so anything already
     * referencing that id - variants, lane tariff pricing, proposals,
     * contracts, bookings, container assets - is left untouched), creates a
     * new row when it doesn't, and any existing row with no matching entry
     * is deleted. Deleting a size/class drops any lane tariff prices built
     * on its variants along with it (those are just draft/live rate-setting
     * entries, not a record of an actual transaction), and also drops any
     * container assets registered against those variants (with their own
     * location history) since an asset can't outlive the size/class it's
     * physically built as - unless one of those assets has already been
     * assigned to a booking, in which case the whole thing is blocked
     * (ContainerAsset -> BookingContainerUnit is a SET NULL fk, not
     * restrict, so deleting the asset would otherwise silently orphan that
     * booking's container assignment instead of failing loudly). Still
     * blocked (restrictOnDelete) if a variant itself is tied to a booking
     * line, proposal or contract - that's real financial history and stays
     * protected. Renaming is never blocked either way. Same pattern as
     * Location::syncPorts()/syncServiceableAreas().
     *
     * Variants are auto-generated, not hand-picked: every size gets a "base"
     * (no class) variant, and if the container has any classes, every class
     * x size pair also gets a variant - matching how container SKUs are
     * actually coded ("CV - 20-GP" vs "CV - 20-GP - A").
     *
     * Loose Cargo / Rolling Cargo don't have a fixed size at all - they're
     * priced by class only (MT/CBM). When a container has classes but no
     * sizes, each class gets its own size-less variant instead.
     *
     * @param  array<int, array{id?: int|null, class: string}>  $classEntries
     * @param  array<int, array{id?: int|null, size: string}>  $sizeEntries
     */
    public function syncCatalog(array $classEntries, array $sizeEntries): void
    {
        $keepSizeIds = [];
        $sizes = collect($this->normalizeCatalogEntries($sizeEntries, 'size'))
            ->map(function ($entry) use (&$keepSizeIds) {
                $size = $this->sizes()->updateOrCreate(['id' => $entry['id']], ['size' => $entry['size']]);
                $keepSizeIds[] = $size->id;

                return $size;
            });

        $keepClassIds = [];
        $classes = collect($this->normalizeCatalogEntries($classEntries, 'class'))
            ->map(function ($entry) use (&$keepClassIds) {
                $class = $this->classes()->updateOrCreate(['id' => $entry['id']], ['class' => $entry['class']]);
                $keepClassIds[] = $class->id;

                return $class;
            });

        $droppedSizeIds = $this->sizes()->whereNotIn('id', $keepSizeIds)->pluck('id');
        $droppedClassIds = $this->classes()->whereNotIn('id', $keepClassIds)->pluck('id');

        // Variants tied to a size/class actually being dropped must go
        // first, otherwise the size/class row's own restrictOnDelete FK
        // would block its deletion below.
        $staleVariants = $this->variants()
            ->where(fn ($q) => $q->whereIn('container_size_id', $droppedSizeIds)
                ->orWhereIn('container_class_id', $droppedClassIds))
            ->get();

        $staleVariantIds = $staleVariants->pluck('id');

        // Lane tariff prices are rate-setting entries built on a variant,
        // not a transaction record - clear them so they don't block the
        // variant delete below.
        LaneTariffRatePrice::whereIn('container_variant_id', $staleVariantIds)->delete();

        $staleAssetIds = ContainerAsset::whereIn('container_variant_id', $staleVariantIds)->pluck('id');

        if (BookingContainerUnit::whereIn('container_asset_id', $staleAssetIds)->exists()) {
            throw new ProtectedRecordException(
                'One or more container assets tied to this size/class have already been assigned to a booking.'
            );
        }

        // Assets are just physical units built as this size/class - once
        // it's gone they can't exist either. Location history cascades
        // automatically at the DB level.
        ContainerAsset::whereIn('id', $staleAssetIds)->delete();

        $staleVariants->each->delete();

        $this->sizes()->whereIn('id', $droppedSizeIds)->delete();
        $this->classes()->whereIn('id', $droppedClassIds)->delete();

        if ($sizes->isNotEmpty()) {
            $desired = $sizes->map(fn ($size) => [
                'container_class_id' => null,
                'container_size_id' => $size->id,
            ]);

            if ($classes->isNotEmpty()) {
                $desired = $desired->concat(
                    $sizes->crossJoin($classes)->map(fn ($pair) => [
                        'container_size_id' => $pair[0]->id,
                        'container_class_id' => $pair[1]->id,
                    ])
                );
            }
        } elseif ($classes->isNotEmpty()) {
            $desired = $classes->map(fn ($class) => [
                'container_size_id' => null,
                'container_class_id' => $class->id,
            ]);
        } else {
            $desired = collect([[
                'container_size_id' => null,
                'container_class_id' => null,
            ]]);
        }

        $comboKey = fn ($sizeId, $classId) => $sizeId . '-' . $classId;

        $existingByCombo = $this->variants()->get()
            ->keyBy(fn ($variant) => $comboKey($variant->container_size_id, $variant->container_class_id));

        $desired
            ->reject(fn ($combo) => $existingByCombo->has($comboKey($combo['container_size_id'], $combo['container_class_id'])))
            ->each(fn ($combo) => $this->variants()->create($combo + ['is_active' => true]));
    }

    /**
     * Trim, drop blanks and de-dupe a syncCatalog() entry list, defaulting
     * a missing/non-numeric id to null (a brand new row).
     */
    protected function normalizeCatalogEntries(array $entries, string $column): array
    {
        return collect($entries)
            ->map(fn ($entry) => [
                'id' => is_numeric($entry['id'] ?? null) ? (int) $entry['id'] : null,
                $column => trim((string) ($entry[$column] ?? '')),
            ])
            ->filter(fn ($entry) => $entry[$column] !== '')
            ->unique($column)
            ->values()
            ->all();
    }
}
