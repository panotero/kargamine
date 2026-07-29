<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmCompanyInfo extends Model
{
    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
    ];

    protected $table = 'crm_company_info';

    protected $appends = ['authorized_signatory_name'];

    protected $fillable = [
        'lead_id',
        'company_name',
        'position',
        'type_of_business',
        'industry_description',
        'authorized_signatory_title',
        'authorized_signatory_first_name',
        'authorized_signatory_middle_name',
        'authorized_signatory_last_name',
        'authorized_signatory_gender',
        'authorized_signatory_position',
        'authorized_signatory_mobile',
        'authorized_signatory_mobile_type',
        'authorized_signatory_landline',
        'authorized_signatory_landline_type',
        'authorized_signatory_email',
        'authorized_signatory_email_type',
    ];

    public function lead()
    {
        return $this->belongsTo(CrmLead::class, 'lead_id');
    }

    /**
     * Computed, read-only - mirrors CrmLead::getContactNameAttribute() so
     * existing display code reading company.authorized_signatory_name
     * keeps working after that column was split into structured fields.
     */
    public function getAuthorizedSignatoryNameAttribute(): string
    {
        return trim(collect([
            $this->authorized_signatory_title,
            $this->authorized_signatory_first_name,
            $this->authorized_signatory_middle_name,
            $this->authorized_signatory_last_name,
        ])->filter()->implode(' ')) ?: '-';
    }
}
