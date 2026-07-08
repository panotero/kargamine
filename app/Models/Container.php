<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    protected $fillable = ['container_type_id', 'code', 'name', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function type()
    {
        return $this->belongsTo(ContainerType::class, 'container_type_id');
    }

    public function variants()
    {
        return $this->hasMany(ContainerVariant::class);
    }
}
