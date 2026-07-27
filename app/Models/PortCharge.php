<?php

namespace App\Models;

use App\Traits\HasEffectivePeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortCharge extends Model
{
    use HasEffectivePeriod;

    protected $primaryKey = 'port_charge_id';

    protected $fillable = [
        'port_id', 'charge_type_id', 'amount',
        'effective_date', 'end_date', 'is_active',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'amount' => 'decimal:2',
        'effective_date' => 'date:M d, Y',
        'end_date' => 'date:M d, Y',
        'is_active' => 'boolean',
    ];

    public function port(): BelongsTo
    {
        return $this->belongsTo(Port::class, 'port_id', 'port_id');
    }

    public function chargeType(): BelongsTo
    {
        return $this->belongsTo(ChargeType::class, 'charge_type_id', 'charge_type_id');
    }
}
