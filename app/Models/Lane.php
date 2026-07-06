<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lane extends Model
{
    protected $primaryKey = 'lane_id';

    protected $fillable = ['origin_port_id', 'destination_port_id', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function originPort(): BelongsTo
    {
        return $this->belongsTo(Port::class, 'origin_port_id', 'port_id');
    }

    public function destinationPort(): BelongsTo
    {
        return $this->belongsTo(Port::class, 'destination_port_id', 'port_id');
    }

    public function tariffRates(): HasMany
    {
        return $this->hasMany(LaneTariffRate::class, 'lane_id', 'lane_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'lane_id', 'lane_id');
    }

    /** Convenience: currently active tariff rate (today). */
    public function currentTariffRate()
    {
        return $this->tariffRates()->activeOn()->orderByDesc('effective_date')->first();
    }
}
