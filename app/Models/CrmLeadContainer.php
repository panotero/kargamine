<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmLeadContainer extends Model
{
    protected $fillable = [
        'lead_id',
        'container_type',
        'origin_port_id',
        'destination_port_id',
        'booking_unit_type',
        'quantity',
        'declared_value_per_unit',
        'frequency',
        'general_cargo_description',
        'container_class_id',
        'container_size_id',
        'minimum_temperature',
        'estimated_cbm',
        'estimated_ton',
        'service_mode_origin',
        'service_mode_destination',
        'service_mode',
        'dangerous_cargo',
        'dg_documentary_requirement',
        'special_requirements',
        'special_notes',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'dangerous_cargo' => 'boolean',
        'declared_value_per_unit' => 'decimal:2',
        'minimum_temperature' => 'decimal:2',
        'estimated_cbm' => 'decimal:2',
        'estimated_ton' => 'decimal:2',
    ];

    public const LABELS = [
        'CV' => 'Container Van',
        'FR' => 'Flatrack',
        'RF' => 'Reefer Van',
        'LC' => 'Loose Cargo',
        'RC' => 'Rolling Cargo',
    ];

    public function lead()
    {
        return $this->belongsTo(CrmLead::class, 'lead_id');
    }

    public function originPort()
    {
        return $this->belongsTo(Port::class, 'origin_port_id', 'port_id');
    }

    public function destinationPort()
    {
        return $this->belongsTo(Port::class, 'destination_port_id', 'port_id');
    }

    public function containerClass()
    {
        return $this->belongsTo(ContainerClass::class, 'container_class_id');
    }

    public function containerSize()
    {
        return $this->belongsTo(ContainerSize::class, 'container_size_id');
    }
}
