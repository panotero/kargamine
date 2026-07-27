<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChargeType extends Model
{
    // Where this charge type can be used
    public const APPLICABLE_PORT = 'PORT';       // usable in Port Charges (tied to a specific port)
    public const APPLICABLE_GENERAL = 'GENERAL'; // usable in General Charges (applies to every booking)

    protected $primaryKey = 'charge_type_id';

    protected $fillable = ['code', 'name', 'applicable_to', 'is_active'];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'is_active' => 'boolean',
    ];

    public function portCharges(): HasMany
    {
        return $this->hasMany(PortCharge::class, 'charge_type_id', 'charge_type_id');
    }

    public function generalCharges(): HasMany
    {
        return $this->hasMany(GeneralCharge::class, 'charge_type_id', 'charge_type_id');
    }

    public function bookingPortCharges(): HasMany
    {
        return $this->hasMany(BookingPortCharge::class, 'charge_type_id', 'charge_type_id');
    }
}
