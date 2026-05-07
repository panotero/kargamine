<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyInfoMaster extends Model
{
    protected $table = 'company_info_master';

    protected $fillable = [
        'customer_code',
        'company_name',
        'registered_address',
        'contact_number_1',
        'contact_number_2',
        'industry',
        'organization_type',
        'tax_identification_number',
        'business_start_date',
        'number_of_employees',
        'synkar',
        'estimated_annual_revenue',
        'estimated_annual_net_income',
        'company_url',
        'customer_type',
    ];

    protected $casts = [
        'business_start_date' => 'date',
        'synkar' => 'boolean',
        'estimated_annual_revenue' => 'decimal:2',
        'estimated_annual_net_income' => 'decimal:2',
    ];

    // Relationships

    public function contacts()
    {
        return $this->hasMany(ContactInfo::class, 'company_id');
    }

    public function tradeReferences()
    {
        return $this->hasMany(TradeReference::class, 'company_id');
    }

    public function services()
    {
        return $this->hasMany(ServicesInfo::class, 'company_id');
    }

    public function finance()
    {
        return $this->hasOne(CompanyFinance::class, 'company_id');
    }

    public function billing()
    {
        return $this->hasOne(BilledDetail::class, 'company_id');
    }

    public function sales()
    {
        return $this->hasOne(SalesInfo::class, 'company_id');
    }

    public function stages()
    {
        return $this->hasOne(StagesInfo::class, 'company_id');
    }
}
