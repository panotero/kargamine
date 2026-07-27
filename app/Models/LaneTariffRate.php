<?php

namespace App\Models;

use App\Traits\HasEffectivePeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaneTariffRate extends Model
{
    use HasEffectivePeriod;

    protected $primaryKey = 'rate_id';

    protected $fillable = [
        'lane_id',
        'effective_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'effective_date' => 'date:M d, Y',
        'end_date' => 'date:M d, Y',
        'is_active' => 'boolean',
    ];

    public function lane(): BelongsTo
    {
        return $this->belongsTo(Lane::class, 'lane_id', 'lane_id');
    }

    /** RA + GRI */
    public function getTotalAdjustmentAttribute(): float
    {
        return (float) $this->ra + (float) $this->gri;
    }

    /** FRT + BSC + total adjustment (no contract discount applied) */
    public function getArtAttribute(): float
    {
        return (float) $this->frt + (float) $this->bsc + $this->total_adjustment;
    }
    public function prices()
    {
        return $this->hasMany(LaneTariffRatePrice::class, 'lane_tariff_rate_id', 'rate_id');
    }
}
