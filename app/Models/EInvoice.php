<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EInvoice extends Model
{
    use HasFactory;

    protected $table = 'e_invoice';

    protected $fillable = [
        'company_id',
        'invoice_email_address',
        'invoice_email_cc_address',
        'invoice_email_bcc_address',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function company()
    {
        return $this->belongsTo(CompanyInfoMaster::class, 'company_id');
    }
}
