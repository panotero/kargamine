<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientProposalRate extends Model
{
    protected $fillable = [
        'proposal_id',
        'origin_port_id',
        'destination_port_id',
        'container_id',
        'container_class_id',
        'container_size_id',
        'container_variant_id',
        'base_rate',
        'discount_type',
        'discount_value',
        'final_rate',
    ];

    public function proposal()
    {
        return $this->belongsTo(ClientProposal::class, 'proposal_id');
    }
    public function originPort()
    {
        return $this->belongsTo(Port::class, 'origin_port_id', 'port_id');
    }
    public function destinationPort()
    {
        return $this->belongsTo(Port::class, 'destination_port_id', 'port_id');
    }
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
    public function variant()
    {
        return $this->belongsTo(ContainerVariant::class, 'container_variant_id');
    }
}
