<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientAncillaryService extends Model
{
    protected $fillable = [
        'client_id',
        'required_service',
        'location',
        'unit',
        'quantity',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'quantity' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(ClientMaster::class, 'client_id');
    }
}
