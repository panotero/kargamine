<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VesselMaintenanceRecord extends Model
{
    protected $fillable = [
        'vessel_id',
        'maintenance_type',
        'performed_at',
        'completed_at',
        'description',
        'performed_by',
        'cost',
        'next_due_at',
        'recorded_by',
    ];

    protected $casts = [
        'performed_at' => 'date:M d, Y',
        'completed_at' => 'date:M d, Y',
        'next_due_at' => 'date:M d, Y',
        'cost' => 'decimal:2',
    ];

    public function vessel(): BelongsTo
    {
        return $this->belongsTo(Vessel::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
