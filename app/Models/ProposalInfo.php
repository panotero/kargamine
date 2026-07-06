<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalInfo extends Model
{
    use HasFactory;

    protected $table = 'proposals_rates';

    protected $fillable = [
        'proposal_id',
        'proposed_rate',
        'route_from',
        'route_to',
        'min_van_qty',
        'container_class',
        'container_type',
        'container_size',
        'origin_service_type',
        'destination_service_type',
    ];
    protected $with = [
        'routeFrom',
        'routeTo',
        'vanClass',
        'vanType',
        'vanSize',
        'serviceOrigin',
        'serviceDestination',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }

    public function routeFrom()
    {

        return $this->belongsTo(Route::class, 'route_from', 'port_id');
    }
    public function routeTo()
    {

        return $this->belongsTo(Route::class, 'route_to', 'port_id');
    }
    public function vanClass()
    {

        return $this->belongsTo(VanClass::class, 'container_class', 'id');
    }

    public function vanType()
    {

        return $this->belongsTo(VanType::class, 'container_type', 'id');
    }
    public function vanSize()
    {

        return $this->belongsTo(VanSize::class, 'container_size', 'id');
    }
    public function serviceOrigin()
    {

        return $this->belongsTo(Service::class, 'origin_service_type', 'id');
    }
    public function serviceDestination()
    {

        return $this->belongsTo(Service::class, 'destination_service_type', 'id');
    }
}
