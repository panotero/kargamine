<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalRequestProductTrucking extends Model
{
    protected $fillable = [
        'proposal_request_id',
        'trucking_cargo_type',
        'quantity',
        'dispatch_mode',
        'origin_prospect_location_id',
        'destination_prospect_location_id',
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

    public function originLocation()
    {
        return $this->belongsTo(ProspectLocation::class, 'origin_prospect_location_id');
    }

    public function destinationLocation()
    {
        return $this->belongsTo(ProspectLocation::class, 'destination_prospect_location_id');
    }

    public function ancillaryServices()
    {
        return $this->morphMany(ProposalRequestProductAncillaryService::class, 'ancillaryable');
    }
}
