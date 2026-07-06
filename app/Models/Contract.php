<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    // Status constants - mirrors the integer status style used on `proposals`
    public const STATUS_DRAFT = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_EXPIRED = 3;
    public const STATUS_TERMINATED = 4;

    protected $fillable = [
        'uuid', 'code', 'proposal_id', 'lead_id',
        'signed_date', 'valid_from', 'valid_to',
        'status', 'signed_document_path', 'created_by',
    ];

    protected $casts = [
        'signed_date' => 'date',
        'valid_from' => 'date',
        'valid_to' => 'date',
        'status' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Contract $contract) {
            $contract->uuid = $contract->uuid ?: (string) \Illuminate\Support\Str::uuid();
        });
    }

    // NOTE: adjust the class name here to match your actual Proposal model.
    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }

    // NOTE: adjust the class name here to match your actual CRM Lead model.
    public function lead(): BelongsTo
    {
        return $this->belongsTo(CrmLead::class, 'lead_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function rates(): HasMany
    {
        return $this->hasMany(ContractRate::class, 'contract_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'contract_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeValidOn($query, $date = null)
    {
        $date = $date ? \Carbon\Carbon::parse($date) : \Carbon\Carbon::today();

        return $query->whereDate('valid_from', '<=', $date)
            ->whereDate('valid_to', '>=', $date);
    }
}
