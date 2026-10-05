<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalRequestProductCharter extends Model
{
    protected $fillable = [
        'proposal_request_id',
        'vessel_name',
        'vessel_dead_weight',
        'charter_start_date',
        'charter_end_date',
        'loading_date',
        'laytime_loading_days',
        'laytime_unloading_days',
        'demurrage_charges',
        'lashing_service',
        'insurance_services',
        'other_charges',
        'terms_of_payment',
        'cargo_manifest_path',
        'declared_value',
        'weight',
        'weight_unit',
    ];

    protected $casts = [
        'demurrage_charges' => 'boolean',
        'lashing_service' => 'boolean',
        'insurance_services' => 'boolean',
        'charter_start_date' => 'date:Y-m-d',
        'charter_end_date' => 'date:Y-m-d',
        'loading_date' => 'date:Y-m-d',
        'created_at' => 'datetime:F j, Y',
        'updated_at' => 'datetime:F j, Y',
    ];

    public function proposalRequest()
    {
        return $this->belongsTo(ProposalRequest::class, 'proposal_request_id');
    }

    public function cargoItems()
    {
        return $this->hasMany(ProposalRequestProductCharterCargo::class, 'proposal_request_product_charter_id');
    }

    public function ports()
    {
        return $this->hasMany(ProposalRequestProductCharterPort::class, 'proposal_request_product_charter_id')->orderBy('sort_order');
    }
}
