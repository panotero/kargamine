<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientContact extends Model
{
    protected $fillable = [
        'client_id',
        'contact_name',
        'contact_number',
        'contact_number_type',
        'contact_email',
        'contact_email_type',
        'role',
        'position',
    ];

    public function client()
    {
        return $this->belongsTo(ClientMaster::class, 'client_id');
    }
}
