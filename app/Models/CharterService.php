<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CharterService extends Model
{
    protected $primaryKey = 'charter_service_id';

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
