<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientContact extends Model
{
    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
    ];

    protected $fillable = [
        'client_id',
        'contact_department',
        'title',
        'first_name',
        'last_name',
        'gender',
        'position',
        'landline_number',
        'landline_type',
        'mobile',
        'mobile_type',
        'email',
        'email_type',
    ];

    public function client()
    {
        return $this->belongsTo(ClientMaster::class, 'client_id');
    }

    public function addresses()
    {
        return $this->hasMany(ClientContactAddress::class, 'contact_id');
    }
}
