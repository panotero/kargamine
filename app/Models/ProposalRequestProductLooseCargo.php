<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalRequestProductLooseCargo extends Model
{
    // Laravel's default guess pluralizes "Cargo" to "Cargos", which
    // doesn't match the migration's actual (singular) table name.
    protected $table = 'proposal_request_product_loose_cargo';

    protected $fillable = [
        'proposal_request_id',
        'cargo_type',
        'cargo_details',
        'cargo_quantity',
        'cargo_units',
        'revenue_ton',
        'revenue_ton_unit',
        'cargo_measurement',
        'delivery_type_id',
        'service_type',
        'origin_prospect_location_id',
        'destination_prospect_location_id',
        'origin_port_id',
        'destination_port_id',
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
