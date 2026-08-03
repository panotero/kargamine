<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientAncillaryService extends Model
{
    protected $fillable = [
        'client_id',
        'client_code',
        'client_mnemonic',
        'client_business_name',
        'required_service',
        'location',
        'unit',
        'unit_rate_vat_ex',
        'mode_of_payment',
        'remarks',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'unit_rate_vat_ex' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(ClientMaster::class, 'client_id');
    }
}
