<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientAddress extends Model
{
    protected $fillable = [
        'client_id',
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

    public function client()
    {
        return $this->belongsTo(ClientMaster::class, 'client_id');
    }
}
