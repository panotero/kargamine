<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Port extends Model
{
    protected $primaryKey = 'port_id';

    protected $fillable = ['code', 'name', 'is_active', 'latitude', 'longitude'];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
    ];

    public function serviceableAreas(): HasMany
    {
        return $this->hasMany(ServiceableArea::class, 'port_id', 'port_id');
    }

    public function portCharges(): HasMany
    {
        return $this->hasMany(PortCharge::class, 'port_id', 'port_id');
    }

    public function handlingFees(): HasMany
    {
        return $this->hasMany(HandlingFee::class, 'port_id', 'port_id');
    }

    public function containerAssets(): HasMany
    {
        return $this->hasMany(ContainerAsset::class, 'current_port_id', 'port_id');
    }

    public function lanesAsOrigin(): HasMany
    {
        return $this->hasMany(Lane::class, 'origin_port_id', 'port_id');
    }

    public function lanesAsDestination(): HasMany
    {
        return $this->hasMany(Lane::class, 'destination_port_id', 'port_id');
    }
}
