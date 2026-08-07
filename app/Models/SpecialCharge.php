<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialCharge extends Model
{
    protected $primaryKey = 'special_charge_id';

    protected $fillable = [
        'name',
        'base_value',
        'is_active',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'base_value' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
