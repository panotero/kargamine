<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavIcon extends Model
{
    protected $fillable = [
        'key',
        'label',
        'svg',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
