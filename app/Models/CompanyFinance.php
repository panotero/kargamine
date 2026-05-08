<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyFinance extends Model
{
    protected $table = 'company_finance';

    protected $fillable = [
        'company_id',
        'credit_terms',
        'payment_mode',
        'standard_billing_service',
        'invoice_email',
        'invoice_email_address',
        'invoice_courier',
        'invoice_courier_address',
        'payment_check_pickup',
        'check_pickup_address',
        'direct_bank_remittance',
        'document_handling',
        'billing_summary_report',
        'other_requests',
        'invoice_mode',
    ];

    protected $casts = [
        'standard_billing_service' => 'boolean',
        'invoice_email' => 'boolean',
        'invoice_courier' => 'boolean',
        'payment_check_pickup' => 'boolean',
        'direct_bank_remittance' => 'boolean',
        'document_handling' => 'boolean',
        'billing_summary_report' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(CompanyInfoMaster::class, 'company_id');
    }
}
