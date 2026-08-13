<?php

namespace App\Models;

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
     * Replace this container's Classes, Sizes and generated Variants in
     * one go. Variants are auto-generated, not hand-picked: every size
     * gets a "base" (no class) variant, and if the container has any
     * classes, every class x size pair also gets a variant - matching how
     * container SKUs are actually coded ("CV - 20-GP" vs "CV - 20-GP - A").
     *
     * Loose Cargo / Rolling Cargo don't have a fixed size at all - they're
     * priced by class only (MT/CBM). When a container has classes but no
     * sizes, each class gets its own size-less variant instead.
     */
    public function syncCatalog(array $classNames, array $sizeNames): void
    {
        $this->variants()->delete();
        $this->classes()->delete();
        $this->sizes()->delete();

        $sizes = collect($sizeNames)
            ->map(fn ($size) => trim($size))
            ->filter()
            ->unique()
            ->map(fn ($size) => $this->sizes()->create(['size' => $size]));

        $classes = collect($classNames)
            ->map(fn ($class) => trim($class))
            ->filter()
            ->unique()
            ->map(fn ($class) => $this->classes()->create(['class' => $class]));

        if ($sizes->isNotEmpty()) {
            $variants = $sizes->map(fn ($size) => [
                'container_class_id' => null,
                'container_size_id' => $size->id,
                'is_active' => true,
            ]);

            if ($classes->isNotEmpty()) {
                $variants = $variants->concat(
                    $sizes->crossJoin($classes)->map(fn ($pair) => [
                        'container_size_id' => $pair[0]->id,
                        'container_class_id' => $pair[1]->id,
                        'is_active' => true,
                    ])
                );
            }
        } elseif ($classes->isNotEmpty()) {
            $variants = $classes->map(fn ($class) => [
                'container_size_id' => null,
                'container_class_id' => $class->id,
                'is_active' => true,
            ]);
        } else {
            $variants = collect([[
                'container_size_id' => null,
                'container_class_id' => null,
                'is_active' => true,
            ]]);
        }

        $this->variants()->createMany($variants->all());
    }
}
