<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceableArea extends Model
{
    protected $primaryKey = 'area_id';

    protected $fillable = ['port_id', 'area_name', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function port(): BelongsTo
    {
        return $this->belongsTo(Port::class, 'port_id', 'port_id');
    }

    public function truckingTariffs(): HasMany
    {
        return $this->hasMany(TruckingTariff::class, 'area_id', 'area_id');
    }
}
