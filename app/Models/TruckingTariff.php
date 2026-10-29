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
        'area_id', 'port_id', 'amount',
        'effective_date', 'end_date', 'is_active',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'amount' => 'decimal:2',
        'effective_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function serviceableArea(): BelongsTo
    {
        return $this->belongsTo(ServiceableArea::class, 'area_id', 'area_id');
    }

    public function port(): BelongsTo
    {
        return $this->belongsTo(Port::class, 'port_id', 'port_id');
    }
}
