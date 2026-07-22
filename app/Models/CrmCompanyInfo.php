<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmCompanyInfo extends Model
{
    protected $table = 'crm_company_info';

    protected $fillable = [
        'lead_id',
        'company_name',
        'position',
        'type_of_business',
        'industry_description',
        'authorized_signatory_name',
        'authorized_signatory_position',
    ];

    public function lead()
    {
        return $this->belongsTo(CrmLead::class, 'lead_id');
    }
}
