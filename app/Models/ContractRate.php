<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractRate extends Model
{
    public const DISCOUNT_PERCENTAGE = 'PERCENTAGE';
    public const DISCOUNT_FIXED = 'FIXED';

    protected $fillable = [
        'contract_id', 'route_from', 'route_to', 'min_van_qty',
        'container_class', 'container_type', 'container_size',
        'origin_service_type', 'destination_service_type',
        'discount_type', 'discount_value', 'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    /**
     * Apply this rate's discount to a given FRT amount.
     */
    public function applyTo(float $frt): float
    {
        if ($this->discount_type === self::DISCOUNT_PERCENTAGE) {
            return $frt - ($frt * (float) $this->discount_value / 100);
        }

        return max(0, $frt - (float) $this->discount_value);
    }
}
