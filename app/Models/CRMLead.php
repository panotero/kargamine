<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CRMLead extends Model
{
    protected $table = 'crm_leads';
    protected $fillable = [
        'contact_name',
        'email',
        'mobile',
        'status',
        'source',
        'assigned_to',
        'estimated_value',
        'expected_close_date',
        'status_updated_at'
    ];

    public function company()
    {
        return $this->hasOne(CompanyInfo::class, 'lead_id');
    }

    public function notes()
    {
        return $this->hasMany(CrmNote::class, 'lead_id');
    }

    public function activities()
    {
        return $this->hasMany(CrmActivity::class, 'lead_id');
    }
}
