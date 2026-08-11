<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $primaryKey = 'location_id';

    protected $fillable = ['name', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
    ];

    public function ports(): HasMany
    {
        return $this->hasMany(Port::class, 'location_id', 'location_id');
    }
}
