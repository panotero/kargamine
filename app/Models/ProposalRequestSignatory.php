<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalRequestSignatory extends Model
{
    protected $fillable = [
        'proposal_request_id',
        'prospect_contact_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:F j, Y',
        'updated_at' => 'datetime:F j, Y',
    ];

    public function proposalRequest()
    {
        return $this->belongsTo(ProposalRequest::class, 'proposal_request_id');
    }

    public function prospectContact()
    {
        return $this->belongsTo(ProspectContact::class, 'prospect_contact_id');
    }
}
