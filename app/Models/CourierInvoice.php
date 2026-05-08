<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierInvoice extends Model
{
    use HasFactory;

    protected $table = 'courier_invoice';

    protected $fillable = [
        'company_id',
        'invoice_contact',
        'invoice_contact_number',
        'invoice_courier_address',
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
