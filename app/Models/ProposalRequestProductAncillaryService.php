<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalRequestProductAncillaryService extends Model
{
    protected $fillable = [
        'ancillaryable_type',
        'ancillaryable_id',
        'ancillary_type',
        'cargo_yard_id',
        'ancillary_unit',
        'ancillary_remarks',
        'special_charge_id',
        'base_value',
        'quantity',
        'adjustment',
        'total_value',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'base_value' => 'decimal:2',
        'quantity' => 'decimal:2',
        'adjustment' => 'decimal:2',
        'total_value' => 'decimal:2',
    ];

    public function ancillaryable()
    {
        return $this->morphTo();
    }

    public function cargoYard()
    {
        return $this->belongsTo(CargoYard::class, 'cargo_yard_id', 'cargo_yard_id');
    }

    public function specialCharge()
    {
        return $this->belongsTo(SpecialCharge::class, 'special_charge_id', 'special_charge_id');
    }
}
