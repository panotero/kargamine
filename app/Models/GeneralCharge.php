<?php

namespace App\Models;

use App\Traits\HasEffectivePeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneralCharge extends Model
{
    use HasEffectivePeriod;

    protected $primaryKey = 'general_charge_id';

    protected $fillable = ['charge_type_id', 'amount', 'effective_date', 'end_date', 'is_active'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'amount' => 'decimal:2',
        'effective_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function chargeType(): BelongsTo
    {
        return $this->belongsTo(ChargeType::class, 'charge_type_id', 'charge_type_id');
    }
}
