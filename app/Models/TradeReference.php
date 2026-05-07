<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradeReference extends Model
{
    protected $table = 'trade_references';

    protected $fillable = [
        'company_id',
        'business_name',
        'relationship',
        'business_address',
        'contact_person_name',
        'contact_phone',
        'contact_email',
    ];

    public function company()
    {
        return $this->belongsTo(CompanyInfoMaster::class, 'company_id');
    }
}
