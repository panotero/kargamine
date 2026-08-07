<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientCommodityDeclaredValue extends Model
{
    protected $fillable = [
        'client_id',
        'commodity_type',
        'max_declared_value',
    ];

    protected $casts = [
        'max_declared_value' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(ClientMaster::class, 'client_id');
    }
}
