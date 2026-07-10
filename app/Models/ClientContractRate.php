<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientContractRate extends Model
{
    protected $fillable = [
        'contract_id',
        'origin_port_id',
        'destination_port_id',
        'container_id',
        'container_class_id',
        'container_size_id',
        'container_variant_id',
        'base_rate',
        'discount_type',
        'discount_value',
        'final_rate',
    ];

    public function contract()
    {
        return $this->belongsTo(ClientContract::class, 'contract_id');
    }
}
