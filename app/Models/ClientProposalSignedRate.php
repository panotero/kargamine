<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Immutable, auditable snapshot of a client_proposal_rates line, captured at
 * the moment a signed document is attached (ClientProposalController::attachSigned).
 * See the create_client_proposal_signed_rates_table migration for why the
 * catalog id columns here are plain, unconstrained ids rather than FKs.
 */
class ClientProposalSignedRate extends Model
{
    protected $fillable = [
        'client_proposal_id',
        'source_rate_id',
        'origin_port_id',
        'origin_pickup_area_id',
        'destination_port_id',
        'destination_pickup_area_id',
        'container_id',
        'container_class_id',
        'container_size_id',
        'container_variant_id',
        'min_van_qty',
        'base_rate',
        'discount_type',
        'discount_value',
        'final_rate',
        'include_special_charges',
        'include_port_charges',
        'include_handling_fee',
        'include_general_charges',
        'ancillary_services',
    ];

    protected $casts = [
        'include_special_charges' => 'boolean',
        'include_port_charges' => 'boolean',
        'include_handling_fee' => 'boolean',
        'include_general_charges' => 'boolean',
        'ancillary_services' => 'array',
    ];

    public function proposal()
    {
        return $this->belongsTo(ClientProposal::class, 'client_proposal_id');
    }
}
