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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}
