<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProspectContact extends Model
{
    protected $fillable = [
        'prospect_id',
        'title',
        'first_name',
        'middle_name',
        'last_name',
        'position',
    ];

    public function prospect()
    {
        return $this->belongsTo(Prospect::class, 'prospect_id');
    }

    public function channels()
    {
        return $this->hasMany(ProspectContactChannel::class, 'prospect_contact_id');
    }

    public function addresses()
    {
        return $this->hasMany(ProspectContactAddress::class, 'prospect_contact_id');
    }
}
