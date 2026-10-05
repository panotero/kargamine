<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProspectLocation extends Model
{
    protected $fillable = [
        'prospect_id',
        'address_type',
        'is_primary',
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
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'is_primary' => 'boolean',
    ];

    protected $appends = ['location_mnemonic'];

    public function prospect()
    {
        return $this->belongsTo(Prospect::class, 'prospect_id');
    }

    /**
     * "barangay, town/city, province" - the short label used wherever this
     * address is picked from a dropdown (Company Details' address picker,
     * Origin & Destination rows) instead of the full field-by-field dump.
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
