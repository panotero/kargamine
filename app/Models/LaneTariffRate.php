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
        'frt',
        'bsc',
        'ra',
        'gri',
        'effective_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'frt' => 'decimal:2',
        'bsc' => 'decimal:2',
        'ra' => 'decimal:2',
        'gri' => 'decimal:2',
        'effective_date' => 'date',
        'end_date' => 'date',
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
}
