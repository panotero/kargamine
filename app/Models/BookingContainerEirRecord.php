<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingContainerEirRecord extends Model
{
    public const DIRECTION_OUT = 'OUT';

    public const DIRECTION_IN = 'IN';

    protected $fillable = [
        'booking_container_unit_id',
        'direction',
        'damage_codes',
        'damage_remarks',
        'convan_checklist_path',
        'damage_photo_paths',
        'convan_class_id',
        'shipper_representative_name',
        'driver_id_photo_path',
        'issued_by',
        'issued_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'issued_at' => 'datetime:M d, Y, h:i A',
        'damage_photo_paths' => 'array',
    ];

    public function containerUnit(): BelongsTo
    {
        return $this->belongsTo(BookingContainerUnit::class, 'booking_container_unit_id');
    }

    public function convanClass(): BelongsTo
    {
        return $this->belongsTo(ContainerClass::class, 'convan_class_id');
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }
}
