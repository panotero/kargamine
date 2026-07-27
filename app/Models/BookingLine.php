<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookingLine extends Model
{
    protected $fillable = [
        'booking_id',
        'container_id',
        'container_class_id',
        'container_size_id',
        'container_variant_id',
        'quantity',
        'description',
        'weight_kg',
        'volume_cbm',
        'is_hazardous',
        'is_fragile',
        'frt_snapshot',
        'discount_type_snapshot',
        'discount_value_snapshot',
        'frt_after_discount_snapshot',
        'line_total',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'quantity' => 'integer',
        'weight_kg' => 'decimal:2',
        'volume_cbm' => 'decimal:2',
        'is_hazardous' => 'boolean',
        'is_fragile' => 'boolean',
        'frt_snapshot' => 'decimal:2',
        'discount_value_snapshot' => 'decimal:2',
        'frt_after_discount_snapshot' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    public function container(): BelongsTo
    {
        return $this->belongsTo(Container::class, 'container_id');
    }

    public function containerClass(): BelongsTo
    {
        return $this->belongsTo(ContainerClass::class, 'container_class_id');
    }

    public function containerSize(): BelongsTo
    {
        return $this->belongsTo(ContainerSize::class, 'container_size_id');
    }

    public function containerVariant(): BelongsTo
    {
        return $this->belongsTo(ContainerVariant::class, 'container_variant_id');
    }

    public function containerUnits(): HasMany
    {
        return $this->hasMany(BookingContainerUnit::class, 'booking_line_id');
    }
}
