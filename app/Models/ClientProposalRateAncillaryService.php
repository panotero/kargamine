<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientProposalRateAncillaryService extends Model
{
    protected $fillable = [
        'proposal_rate_id',
        'required_service',
        'location',
        'unit',
        'quantity',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'quantity' => 'decimal:2',
    ];

    public function proposalRate()
    {
        return $this->belongsTo(ClientProposalRate::class, 'proposal_rate_id');
    }
}
