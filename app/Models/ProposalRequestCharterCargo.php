<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalRequestCharterCargo extends Model
{
    protected $fillable = [
        'proposal_request_charter_id',
        'cargo_type',
        'general_cargo_description',
        'special_requirements',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function charter()
    {
        return $this->belongsTo(ProposalRequestCharter::class, 'proposal_request_charter_id');
    }
}
