<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProspectContactChannel extends Model
{
    protected $fillable = [
        'prospect_contact_id',
        'channel_type',
        'value',
        'contact_type',
    ];

    public function contact()
    {
        return $this->belongsTo(ProspectContact::class, 'prospect_contact_id');
    }
}
