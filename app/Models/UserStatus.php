<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStatus extends Model
{
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    use HasFactory;

    protected $table = 'user_status';

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
    ];
}
