<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientTradeReference extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'business_name',
        'relationship',
        'contact_person_name',
        'contact_person_phone',
        'contact_person_mobile',
        'contact_person_email',
    ];

    public function client()
    {
        return $this->belongsTo(ClientMaster::class, 'client_id');
    }
}
