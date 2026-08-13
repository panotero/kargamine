<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceableArea extends Model
{
    protected $primaryKey = 'area_id';

    protected $fillable = ['location_id', 'area_name', 'is_active'];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'is_active' => 'boolean',
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id', 'location_id');
    }

    public function truckingTariffs(): HasMany
    {
        return $this->hasMany(TruckingTariff::class, 'area_id', 'area_id');
    }
}
