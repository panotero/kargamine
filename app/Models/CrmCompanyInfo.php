<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmCompanyInfo extends Model
{

    protected $table = 'crm_company_info';
    protected $fillable = [
        'lead_id',
        'company_name',
        'position',
        'company_address',
        'address_no',
        'address_building',
        'address_street',
        'address_barangay',
        'address_town_city',
        'address_province',
        'address_country',
        'address_postal_code',
        'type_of_business',
        'authorized_signatory_name',
        'authorized_signatory_position',
    ];

    public function lead()
    {
        return $this->belongsTo(CrmLead::class, 'lead_id');
    }
}
