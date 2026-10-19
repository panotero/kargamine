<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalRequestTermsOfPayment extends Model
{
    protected $table = 'proposal_request_terms_of_payment';

    protected $fillable = [
        'proposal_request_id',
        'section',
        'terms_of_payment',
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
