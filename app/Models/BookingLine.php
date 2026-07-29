<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BookingLine extends Model
{
    protected $fillable = [
        'booking_id',
        'origin_port_id',
        'destination_port_id',
        'origin_area_id',
        'destination_area_id',
        'delivery_type_id',
        'lane_id',
        'tariff_rate_id',
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
        'trucking_snapshot',
        'consignee_name',
        'consignee_address',
        'consignee_contact_person',
        'consignee_contact_number',
        'cargo_type',
        'other_cargo_details',
        'declared_value',
        'delivery_date',
        'delivery_date_notes',
        'first_delivery_date',
        'last_delivery_date',
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
        'trucking_snapshot' => 'decimal:2',
        'declared_value' => 'decimal:2',
        // ISO format (not the display 'M d, Y' style used elsewhere) so
        // these round-trip directly into <input type="date"> on the
        // booking form's edit-prefill without a JS reformat step.
        'delivery_date' => 'date:Y-m-d',
        'first_delivery_date' => 'date:Y-m-d',
        'last_delivery_date' => 'date:Y-m-d',
    ];

    /**
     * The SOP's minimum bar for "this line has transaction details" -
     * matches the fields CSR is asked to confirm in Step 2. Kept as one
     * place both Booking::hasTransactionDetails() and any future UI
     * checklist can call.
     */
    public function hasTransactionDetails(): bool
    {
        return (bool) ($this->consignee_name && $this->cargo_type && $this->declared_value !== null && $this->delivery_date);
    }

    /**
     * SOP Step 3 routing rule (confirmed): ATW for Door-Door/Door-Pier or
     * a client flagged always_route_atw (the SOP's "Trigo" special case,
     * generalized to any client); CAN for Pier-Door/Pier-Pier otherwise.
     * "Door" on the ORIGIN leg is what actually decides it - Door-Door and
     * Door-Pier both have a door origin, Pier-Door and Pier-Pier both have
     * a pier origin - so this only needs to check includes_origin_trucking.
     */
    public function needsAtw(): bool
    {
        return (bool) ($this->deliveryType?->includes_origin_trucking) || (bool) ($this->booking?->client?->always_route_atw);
    }

    public function needsCan(): bool
    {
        return ! $this->needsAtw();
    }

    public function dispatchDocumentType(): string
    {
        return $this->needsAtw() ? BookingDispatchDocument::TYPE_ATW : BookingDispatchDocument::TYPE_CAN;
    }

    public function dispatchDocument(): HasOne
    {
        return $this->hasOne(BookingDispatchDocument::class, 'booking_line_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    public function originPort(): BelongsTo
    {
        return $this->belongsTo(Port::class, 'origin_port_id', 'port_id');
    }

    public function destinationPort(): BelongsTo
    {
        return $this->belongsTo(Port::class, 'destination_port_id', 'port_id');
    }

    public function originArea(): BelongsTo
    {
        return $this->belongsTo(ServiceableArea::class, 'origin_area_id', 'area_id');
    }

    public function destinationArea(): BelongsTo
    {
        return $this->belongsTo(ServiceableArea::class, 'destination_area_id', 'area_id');
    }

    public function deliveryType(): BelongsTo
    {
        return $this->belongsTo(DeliveryType::class, 'delivery_type_id', 'delivery_type_id');
    }

    public function lane(): BelongsTo
    {
        return $this->belongsTo(Lane::class, 'lane_id', 'lane_id');
    }

    public function tariffRate(): BelongsTo
    {
        return $this->belongsTo(LaneTariffRate::class, 'tariff_rate_id', 'rate_id');
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

    public function portCharges(): HasMany
    {
        return $this->hasMany(BookingPortCharge::class, 'booking_line_id');
    }
}
