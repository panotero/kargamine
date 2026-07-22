<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmLeadAddress extends Model
{
    protected $fillable = [
        'lead_id',
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
        'is_primary' => 'boolean',
    ];

    public function lead()
    {
        return $this->belongsTo(CrmLead::class, 'lead_id');
    }
}
