<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CargoYard extends Model
{
    protected $primaryKey = 'cargo_yard_id';

    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'is_active' => 'boolean',
    ];
}
