<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingPortCharge extends Model
{
    protected $primaryKey = 'booking_port_charge_id';

    protected $fillable = ['booking_id', 'port_id', 'charge_type_id', 'role', 'amount_snapshot'];

    protected $casts = [
        'amount_snapshot' => 'decimal:2',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    public function port(): BelongsTo
    {
        return $this->belongsTo(Port::class, 'port_id', 'port_id');
    }

    public function chargeType(): BelongsTo
    {
        return $this->belongsTo(ChargeType::class, 'charge_type_id', 'charge_type_id');
    }
}
