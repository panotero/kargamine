<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientBilling extends Model
{
    protected $table = 'client_billing';

    protected $fillable = ['client_id', 'billed_to', 'company_name', 'address', 'tin'];

    public function client()
    {
        return $this->belongsTo(ClientMaster::class, 'client_id');
    }
}
