<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalRequestLocation extends Model
{
    protected $fillable = [
        'proposal_request_id',
        'prospect_location_id',
        'address_type',
        'address_no',
        'address_building',
        'address_street',
        'address_barangay',
        'address_town_city',
        'address_province',
        'address_country',
        'address_postal_code',
    ];

    protected $casts = [
        'created_at' => 'datetime:F j, Y',
        'updated_at' => 'datetime:F j, Y',
    ];

    protected $appends = ['location_mnemonic'];

    public function proposalRequest()
    {
        return $this->belongsTo(ProposalRequest::class, 'proposal_request_id');
    }

    /**
     * The prospect address this entry was originally seeded from, if any -
     * an optional audit trail only. This row now owns its own address
     * snapshot (see $fillable above), independent of this relation and of
     * whatever the seed address looks like today.
     */
    public function prospectLocation()
    {
        return $this->belongsTo(ProspectLocation::class, 'prospect_location_id');
    }

    /**
     * "barangay, town/city, province" - mirrors ProspectLocation's own
     * accessor, now computed off this row's own snapshot fields instead of
     * the (possibly null) prospectLocation() relation.
     */
    public function getLocationMnemonicAttribute(): string
    {
        return implode(', ', array_filter([
            $this->address_barangay,
            $this->address_town_city,
            $this->address_province,
        ]));
    }
}
