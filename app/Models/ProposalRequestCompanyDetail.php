<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalRequestCompanyDetail extends Model
{
    protected $fillable = [
        'proposal_request_id',
        'company_name',
        'prospect_location_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:F j, Y',
        'updated_at' => 'datetime:F j, Y',
    ];

    public function proposalRequest()
    {
        return $this->belongsTo(ProposalRequest::class, 'proposal_request_id');
    }

    public function prospectLocation()
    {
        return $this->belongsTo(ProspectLocation::class, 'prospect_location_id');
    }
}
