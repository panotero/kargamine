<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalRequestProductAncillaryService extends Model
{
    protected $fillable = [
        'ancillaryable_type',
        'ancillaryable_id',
        'ancillary_type',
        'cargo_yard_id',
        'ancillary_unit',
        'ancillary_remarks',
    ];

    protected $casts = [
        'created_at' => 'datetime:F j, Y',
        'updated_at' => 'datetime:F j, Y',
    ];

    public function ancillaryable()
    {
        return $this->morphTo();
    }

    public function cargoYard()
    {
        return $this->belongsTo(CargoYard::class, 'cargo_yard_id', 'cargo_yard_id');
    }
}
