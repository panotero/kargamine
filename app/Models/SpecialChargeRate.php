<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpecialChargeRate extends Model
{
    protected $fillable = [
        'special_charge_id',
        'unit',
        'base_value',
    ];

    protected $casts = [
        'base_value' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function specialCharge(): BelongsTo
    {
        return $this->belongsTo(SpecialCharge::class, 'special_charge_id', 'special_charge_id');
    }
}
