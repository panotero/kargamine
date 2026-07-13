<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmLeadContainer extends Model
{
    protected $fillable = [
        'lead_id',
        'container_type',
        'origin',
        'destination',
        'booking_unit_type',
        'quantity',
        'declared_value_per_unit',
        'frequency',
        'general_cargo_description',
        'convan_class',
        'convan_size',
        'required_temperature',
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
        'dangerous_cargo' => 'boolean',
        'declared_value_per_unit' => 'decimal:2',
        'required_temperature' => 'decimal:2',
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
}
