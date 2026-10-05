<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalRequestCharter extends Model
{
    protected $fillable = [
        'prospect_id',
        'charter_start_date',
        'charter_end_date',
        'declared_value',
        'weight',
        'weight_unit',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'charter_start_date' => 'date:Y-m-d',
        'charter_end_date' => 'date:Y-m-d',
        'declared_value' => 'decimal:2',
        'weight' => 'decimal:2',
    ];

    public function prospect()
    {
        return $this->belongsTo(Prospect::class, 'prospect_id');
    }

    public function cargoItems()
    {
        return $this->hasMany(ProposalRequestCharterCargo::class, 'proposal_request_charter_id');
    }

    public function ports()
    {
        return $this->hasMany(ProposalRequestCharterPort::class, 'proposal_request_charter_id')->orderBy('sort_order');
    }
}
