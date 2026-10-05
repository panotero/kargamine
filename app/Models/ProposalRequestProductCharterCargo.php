<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalRequestProductCharterCargo extends Model
{
    // Laravel's default guess pluralizes "Cargo" to "Cargos", which
    // doesn't match the migration's actual (singular) table name.
    protected $table = 'proposal_request_product_charter_cargo';

    protected $fillable = [
        'proposal_request_product_charter_id',
        'cargo_type',
        'cargo_description',
        'special_requirements',
    ];

    protected $casts = [
        'created_at' => 'datetime:F j, Y',
        'updated_at' => 'datetime:F j, Y',
    ];

    public function charter()
    {
        return $this->belongsTo(ProposalRequestProductCharter::class, 'proposal_request_product_charter_id');
    }
}
