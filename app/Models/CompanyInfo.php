<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model
{
    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
    ];

    protected $table = 'crm_company_info';
    protected $fillable = [
        'lead_id',
        'company_name',
        'position'
    ];

    public function lead()
    {
        return $this->belongsTo(CrmLead::class, 'lead_id');
    }
}
