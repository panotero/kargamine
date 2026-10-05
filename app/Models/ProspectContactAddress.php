<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProspectContactAddress extends Model
{
    protected $fillable = [
        'prospect_contact_id',
        'prospect_location_id',
    ];

    public function contact()
    {
        return $this->belongsTo(ProspectContact::class, 'prospect_contact_id');
    }

    public function location()
    {
        return $this->belongsTo(ProspectLocation::class, 'prospect_location_id');
    }
}
