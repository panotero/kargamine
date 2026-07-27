<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientBilling extends Model
{
    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
    ];

    protected $table = 'client_billing';

    protected $fillable = ['client_id', 'billed_to', 'company_name', 'address', 'tin'];

    public function client()
    {
        return $this->belongsTo(ClientMaster::class, 'client_id');
    }
}
