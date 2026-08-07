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
        'client_business_name',
        'tin_number',
        'tin_registered_address',
        'registered_tax_type',
        'withholding_tax_code',
        'trade_name',
        'tin_registration_date',
        'line_of_business',
        'tax_percent',
        'withholding_tax_percent',
        'mode_of_payment',
        'cro',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'tin_registration_date' => 'date',
        'tax_percent' => 'decimal:2',
        'withholding_tax_percent' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(ClientMaster::class, 'client_id');
    }
}
