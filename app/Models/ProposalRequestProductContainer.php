<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalRequestProductContainer extends Model
{
    protected $fillable = [
        'proposal_request_id',
        'container_type',
        'container_size_id',
        'minimum_temperature',
        'delivery_type_id',
        'service_type',
        'quantity',
        'origin_prospect_location_id',
        'destination_prospect_location_id',
        'origin_port_id',
        'destination_port_id',
        'dispatch_mode_origin',
        'dispatch_mode_destination',
        'cargo_type',
        'cargo_description',
        'terms_of_payment',
    ];

    protected $casts = [
        'created_at' => 'datetime:F j, Y',
        'updated_at' => 'datetime:F j, Y',
    ];

    public function proposalRequest()
    {
        return $this->belongsTo(ProposalRequest::class, 'proposal_request_id');
    }

    public function containerSize()
    {
        return $this->belongsTo(ContainerSize::class, 'container_size_id');
    }

    public function deliveryType()
    {
        return $this->belongsTo(DeliveryType::class, 'delivery_type_id', 'delivery_type_id');
    }

    public function originLocation()
    {
        return $this->belongsTo(ProspectLocation::class, 'origin_prospect_location_id');
    }

    public function destinationLocation()
    {
        return $this->belongsTo(ProspectLocation::class, 'destination_prospect_location_id');
    }

    public function originPort()
    {
        return $this->belongsTo(Port::class, 'origin_port_id', 'port_id');
    }

    public function destinationPort()
    {
        return $this->belongsTo(Port::class, 'destination_port_id', 'port_id');
    }

    public function ancillaryServices()
    {
        return $this->morphMany(ProposalRequestProductAncillaryService::class, 'ancillaryable');
    }
}
