<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientContactAddress extends Model
{
    protected $fillable = [
        'contact_id',
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

    public function contact()
    {
        return $this->belongsTo(ClientContact::class, 'contact_id');
    }
}
