<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalRequestAncillaryFee extends Model
{
    protected $fillable = [
        'proposal_request_id',
        'section',
        'service_name',
        'fee_amount',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function proposalRequest()
    {
        return $this->belongsTo(ProposalRequest::class, 'proposal_request_id');
    }
}
