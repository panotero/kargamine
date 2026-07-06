<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChargeType extends Model
{
    protected $primaryKey = 'charge_type_id';

    protected $fillable = ['code', 'name', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function portCharges(): HasMany
    {
        return $this->hasMany(PortCharge::class, 'charge_type_id', 'charge_type_id');
    }

    public function bookingPortCharges(): HasMany
    {
        return $this->hasMany(BookingPortCharge::class, 'charge_type_id', 'charge_type_id');
    }
}
