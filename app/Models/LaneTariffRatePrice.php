<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaneTariffRatePrice extends Model
{
    protected $fillable = ['lane_tariff_rate_id', 'container_variant_id', 'frt'];

    public function laneTariffRate()
    {
        return $this->belongsTo(LaneTariffRate::class, 'lane_tariff_rate_id', 'rate_id');
    }

    public function variant()
    {
        return $this->belongsTo(ContainerVariant::class, 'container_variant_id');
    }
}
