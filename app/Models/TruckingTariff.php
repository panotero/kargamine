<?php

namespace App\Models;

use App\Traits\HasEffectivePeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TruckingTariff extends Model
{
    use HasEffectivePeriod;

    protected $primaryKey = 'trucking_tariff_id';

    protected $fillable = [
        'area_id', 'delivery_type_id', 'amount',
        'effective_date', 'end_date', 'is_active',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'effective_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function serviceableArea(): BelongsTo
    {
        return $this->belongsTo(ServiceableArea::class, 'area_id', 'area_id');
    }

    public function deliveryType(): BelongsTo
    {
        return $this->belongsTo(DeliveryType::class, 'delivery_type_id', 'delivery_type_id');
    }
}
