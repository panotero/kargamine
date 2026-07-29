<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryType extends Model
{
    protected $primaryKey = 'delivery_type_id';

    protected $fillable = [
        'code',
        'name',
        'includes_origin_trucking',
        'includes_destination_trucking',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'includes_origin_trucking' => 'boolean',
        'includes_destination_trucking' => 'boolean',
    ];

    public function truckingTariffs(): HasMany
    {
        return $this->hasMany(TruckingTariff::class, 'delivery_type_id', 'delivery_type_id');
    }

    public function bookingLines(): HasMany
    {
        return $this->hasMany(BookingLine::class, 'delivery_type_id', 'delivery_type_id');
    }
}
