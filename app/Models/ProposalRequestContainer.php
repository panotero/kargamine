<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalRequestContainer extends Model
{
    protected $fillable = [
        'prospect_id',
        'container_type',
        'container_size_id',
        'delivery_type_id',
        'service_type',
        'origin_location_id',
        'destination_location_id',
        'quantity',
        'minimum_temperature',
        'revenue_ton',
        'cargo_measurement',
        'cargo_type',
        'general_cargo_description',
        'special_requirements',
        'declared_value_per_unit',
        'weight',
        'weight_unit',
        'frequency',
        'booking_unit_type',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'declared_value_per_unit' => 'decimal:2',
        'minimum_temperature' => 'decimal:2',
        'revenue_ton' => 'decimal:2',
        'weight' => 'decimal:2',
    ];

    public function prospect()
    {
        return $this->belongsTo(Prospect::class, 'prospect_id');
    }

    public function deliveryType()
    {
        return $this->belongsTo(DeliveryType::class, 'delivery_type_id', 'delivery_type_id');
    }

    public function originLocation()
    {
        return $this->belongsTo(Location::class, 'origin_location_id', 'location_id');
    }

    public function destinationLocation()
    {
        return $this->belongsTo(Location::class, 'destination_location_id', 'location_id');
    }

    public function containerSize()
    {
        return $this->belongsTo(ContainerSize::class, 'container_size_id');
    }
}
