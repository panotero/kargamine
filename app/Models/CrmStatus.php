<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmStatus extends Model
{
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $table = 'crm_status';

    protected $fillable = [
        'status',
        'description',
    ];
}
