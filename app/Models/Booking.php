<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $primaryKey = 'booking_id';

    protected $fillable = [
        'lane_id', 'origin_area_id', 'destination_area_id', 'delivery_type_id',
        'tariff_rate_id', 'vat_rate_id', 'contract_id', 'contract_rate_id',
        'frt_snapshot', 'bsc_snapshot', 'ra_snapshot', 'gri_snapshot',
        'discount_type_snapshot', 'discount_value_snapshot', 'frt_after_discount_snapshot',
        'art_snapshot', 'trucking_snapshot', 'vat_amount_snapshot', 'grand_total_snapshot',
        'booking_date', 'created_by',
    ];

    protected $casts = [
        'frt_snapshot' => 'decimal:2',
        'bsc_snapshot' => 'decimal:2',
        'ra_snapshot' => 'decimal:2',
        'gri_snapshot' => 'decimal:2',
        'discount_value_snapshot' => 'decimal:2',
        'frt_after_discount_snapshot' => 'decimal:2',
        'art_snapshot' => 'decimal:2',
        'trucking_snapshot' => 'decimal:2',
        'vat_amount_snapshot' => 'decimal:2',
        'grand_total_snapshot' => 'decimal:2',
        'booking_date' => 'date',
    ];

    public function lane(): BelongsTo
    {
        return $this->belongsTo(Lane::class, 'lane_id', 'lane_id');
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

    public function tariffRate(): BelongsTo
    {
        return $this->belongsTo(LaneTariffRate::class, 'tariff_rate_id', 'rate_id');
    }

    public function vatRate(): BelongsTo
    {
        return $this->belongsTo(VatRate::class, 'vat_rate_id', 'vat_rate_id');
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function contractRate(): BelongsTo
    {
        return $this->belongsTo(ContractRate::class, 'contract_rate_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function portCharges(): HasMany
    {
        return $this->hasMany(BookingPortCharge::class, 'booking_id', 'booking_id');
    }
}
