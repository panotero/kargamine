<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingContainerUnit extends Model
{
    public const STATUS_PENDING = 1;
    public const STATUS_LOADED = 2;
    public const STATUS_DISCHARGED = 3;
    public const STATUS_EXCEPTION = 4;

    public const STATUS_LABELS = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_LOADED => 'Loaded',
        self::STATUS_DISCHARGED => 'Discharged',
        self::STATUS_EXCEPTION => 'Exception',
    ];

    protected $fillable = [
        'booking_line_id',
        'booking_id',
        'unit_index',
        'gate_pass_code',
        'container_asset_id',
        'seal_no',
        'status',
        'origin_port_id',
        'destination_port_id',
    ];

    protected $casts = [
        'unit_index' => 'integer',
        'status' => 'integer',
    ];

    public function bookingLine(): BelongsTo
    {
        return $this->belongsTo(BookingLine::class, 'booking_line_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    public function containerAsset(): BelongsTo
    {
        return $this->belongsTo(ContainerAsset::class, 'container_asset_id');
    }

    public function originPort(): BelongsTo
    {
        return $this->belongsTo(Port::class, 'origin_port_id', 'port_id');
    }

    public function destinationPort(): BelongsTo
    {
        return $this->belongsTo(Port::class, 'destination_port_id', 'port_id');
    }
}
