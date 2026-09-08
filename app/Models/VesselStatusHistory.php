<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VesselStatusHistory extends Model
{
    protected $fillable = [
        'vessel_id',
        'from_status',
        'to_status',
        'notes',
        'recorded_by',
        'recorded_at',
    ];

    protected $casts = [
        'from_status' => 'integer',
        'to_status' => 'integer',
        'recorded_at' => 'datetime:M d, Y, h:i A',
    ];

    public function vessel(): BelongsTo
    {
        return $this->belongsTo(Vessel::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function fromStatusLabel(): ?string
    {
        return $this->from_status ? (Vessel::STATUS_LABELS[$this->from_status] ?? (string) $this->from_status) : null;
    }

    public function toStatusLabel(): string
    {
        return Vessel::STATUS_LABELS[$this->to_status] ?? (string) $this->to_status;
    }
}
