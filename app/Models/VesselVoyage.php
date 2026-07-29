<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VesselVoyage extends Model
{
    protected $fillable = [
        'vessel_name',
        'voyage_mnemonic',
        'voyage_leg',
        'origin_port_id',
        'destination_port_id',
        'estimated_departure_at',
        'estimated_arrival_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'estimated_departure_at' => 'datetime:M d, Y, h:i A',
        'estimated_arrival_at' => 'datetime:M d, Y, h:i A',
    ];

    public function originPort(): BelongsTo
    {
        return $this->belongsTo(Port::class, 'origin_port_id', 'port_id');
    }

    public function destinationPort(): BelongsTo
    {
        return $this->belongsTo(Port::class, 'destination_port_id', 'port_id');
    }

    public function containerUnits(): HasMany
    {
        return $this->hasMany(BookingContainerUnit::class, 'vessel_voyage_id');
    }
}
