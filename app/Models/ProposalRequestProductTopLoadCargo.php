<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalRequestProductTopLoadCargo extends Model
{
    // Laravel's default guess ("proposal_request_product_top_load_cargos")
    // doesn't match the migration's actual table name (no underscore
    // between "top"/"load", and singular "cargo").
    protected $table = 'proposal_request_product_topload_cargo';

    protected $fillable = [
        'proposal_request_product_rolling_cargo_id',
        'top_load_type',
        'details',
        'quantity',
        'units',
        'revenue_ton',
        'revenue_ton_unit',
        'measurement',
    ];

    protected $casts = [
        'created_at' => 'datetime:F j, Y',
        'updated_at' => 'datetime:F j, Y',
    ];

    public function rollingCargo()
    {
        return $this->belongsTo(ProposalRequestProductRollingCargo::class, 'proposal_request_product_rolling_cargo_id');
    }
}
