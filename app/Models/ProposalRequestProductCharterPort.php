<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalRequestProductCharterPort extends Model
{
    protected $fillable = [
        'proposal_request_product_charter_id',
        'port_id',
        'port_charge_account',
        'port_charge_amount',
        'cargoes_for_loading',
        'cargo_measurement_for_loading',
        'revenue_ton_unit_loading',
        'cargoes_for_unloading',
        'cargo_measurement_for_unloading',
        'revenue_ton_unit_unloading',
        'sort_order',
    ];

    protected $casts = [
        'created_at' => 'datetime:F j, Y',
        'updated_at' => 'datetime:F j, Y',
    ];

    public function charter()
    {
        return $this->belongsTo(ProposalRequestProductCharter::class, 'proposal_request_product_charter_id');
    }

    public function port()
    {
        return $this->belongsTo(Port::class, 'port_id', 'port_id');
    }
}
