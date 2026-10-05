<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalRequestTrucking extends Model
{
    protected $fillable = [
        'prospect_id',
        'trucking_cargo_type',
        'quantity',
        'frequency',
        'dispatch_mode',
        'origin_location_id',
        'destination_location_id',
        'declared_value_per_unit',
        'weight',
        'weight_unit',
        'cargo_type',
        'general_cargo_description',
        'special_requirements',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'declared_value_per_unit' => 'decimal:2',
        'weight' => 'decimal:2',
    ];

    public function prospect()
    {
        return $this->belongsTo(Prospect::class, 'prospect_id');
    }

    public function originLocation()
    {
        return $this->belongsTo(Location::class, 'origin_location_id', 'location_id');
    }

    public function destinationLocation()
    {
        return $this->belongsTo(Location::class, 'destination_location_id', 'location_id');
    }
}
