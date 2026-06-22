<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class CrmLead extends Model
{
    use HasUuids;
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


    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function company()
    {
        return $this->hasOne(CrmCompanyInfo::class, 'lead_id', 'id');
    }

    public function notes()
    {
        return $this->hasMany(CrmNote::class, 'lead_id')->orderBy('created_at', 'desc');
    }

    public function activities()
    {
        return $this->hasMany(CrmActivity::class, 'lead_id')->orderBy('created_at', 'desc');
    }
    public function status()
    {
        return $this->hasOne(CrmStatus::class, 'id', 'status');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'assigned_to');
    }

    public function proposals()
    {
        return $this->hasMany(Proposal::class,  'lead_id');
    }
}
