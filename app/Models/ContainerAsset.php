<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContainerAsset extends Model
{
    public const STATUS_AVAILABLE = 1;

    public const STATUS_BOOKED = 2;

    public const STATUS_IN_TRANSIT = 3;

    public const STATUS_UNDER_REPAIR = 4;

    public const STATUS_DAMAGED = 5;

    public const STATUS_OUT_OF_SERVICE = 6;

    public const STATUS_LABELS = [
        self::STATUS_AVAILABLE => 'Available',
        self::STATUS_BOOKED => 'Booked',
        self::STATUS_IN_TRANSIT => 'In Transit',
        self::STATUS_UNDER_REPAIR => 'Under Repair',
        self::STATUS_DAMAGED => 'Damaged',
        self::STATUS_OUT_OF_SERVICE => 'Out of Service',
    ];

    protected $fillable = [
        'container_variant_id',
        'container_no',
        'status',
        'current_port_id',
        'current_pier_reference',
        'last_movement_at',
        'condition_notes',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'status' => 'integer',
        'last_movement_at' => 'datetime:M d, Y, h:i A',
    ];

    public function containerVariant(): BelongsTo
    {
        return $this->belongsTo(ContainerVariant::class);
    }

    public function currentPort(): BelongsTo
    {
        return $this->belongsTo(Port::class, 'current_port_id', 'port_id');
    }

    public function locationHistory(): HasMany
    {
        return $this->hasMany(ContainerAssetLocationHistory::class)->orderByDesc('recorded_at');
    }

    /**
     * Apply a status and/or location change and append exactly one
     * location_history row capturing the result, whatever combination
     * of fields actually changed. Silently skips the history write if
     * the asset still has no known port (nothing to log against).
     */
    public function applyChange(array $attributes, string $source, ?int $recordedBy = null): ?ContainerAssetLocationHistory
    {
        if (array_key_exists('status', $attributes)) {
            $this->status = $attributes['status'];
        }

        if (array_key_exists('current_port_id', $attributes)) {
            $this->current_port_id = $attributes['current_port_id'];
        }

        if (array_key_exists('current_pier_reference', $attributes)) {
            $this->current_pier_reference = $attributes['current_pier_reference'];
        }

        $this->last_movement_at = now();
        $this->save();

        if (! $this->current_port_id) {
            return null;
        }

        return $this->locationHistory()->create([
            'port_id' => $this->current_port_id,
            'pier_reference' => $this->current_pier_reference,
            'status_at_time' => self::STATUS_LABELS[$this->status] ?? (string) $this->status,
            'source' => $source,
            'recorded_by' => $recordedBy,
            'recorded_at' => now(),
        ]);
    }
}
