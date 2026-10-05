<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalRequestCharterPort extends Model
{
    protected $fillable = [
        'proposal_request_charter_id',
        'port_id',
        'sort_order',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
    ];

    public function charter()
    {
        return $this->belongsTo(ProposalRequestCharter::class, 'proposal_request_charter_id');
    }

    public function port()
    {
        return $this->belongsTo(Port::class, 'port_id', 'port_id');
    }
}
