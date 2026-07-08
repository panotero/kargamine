<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContainerVariant extends Model
{
    protected $fillable = ['container_id', 'container_class_id', 'container_size_id', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    public function containerClass()
    {
        return $this->belongsTo(ContainerClass::class, 'container_class_id');
    }

    public function containerSize()
    {
        return $this->belongsTo(ContainerSize::class, 'container_size_id');
    }

    public function prices()
    {
        return $this->hasMany(LaneTariffRatePrice::class);
    }
}
