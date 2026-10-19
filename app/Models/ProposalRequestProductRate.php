<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalRequestProductRate extends Model
{
    protected $fillable = [
        'proposal_request_id',
        'product_type',
        'product_id',
        'item_no',
        'base_rate',
        'adjustment',
        'total_rate',
        'fee_breakdown',
    ];

    protected $casts = [
        'fee_breakdown' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function proposalRequest()
    {
        return $this->belongsTo(ProposalRequest::class, 'proposal_request_id');
    }
}
