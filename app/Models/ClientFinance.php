<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientFinance extends Model
{
    protected $table = 'client_finance';

    protected $fillable = [
        'client_id',
        'credit_terms',
        'payment_mode',
        'standard_billing_service',
        'invoice_submission',
        'invoice_email_address',
        'invoice_courier_recipient',
        'invoice_courier_contact',
        'invoice_courier_address',
        'payment_method',
        'check_pickup_address',
        'bank_name',
        'bank_account_number',
        'document_handling',
        'billing_summary_report',
        'other_requests',
    ];

    protected $casts = [
        'standard_billing_service' => 'boolean',
        'document_handling' => 'boolean',
        'billing_summary_report' => 'boolean',
    ];

    public function client()
    {
        return $this->belongsTo(ClientMaster::class, 'client_id');
    }
}
