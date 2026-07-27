<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientContract extends Model
{
    public const STATUS_DRAFT = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_EXPIRED = 3;
    public const STATUS_TERMINATED = 4;

    public const STATUS_LABELS = [
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_EXPIRED => 'Expired',
        self::STATUS_TERMINATED => 'Terminated',
    ];

    protected $fillable = [
        'uuid',
        'code',
        'client_id',
        'client_proposal_id',
        'signed_date',
        'valid_from',
        'valid_to',
        'status',
        'signed_document_path',
        'created_by',
        'terminated_reason',
        'terminated_by',
        'terminated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'signed_date' => 'date:M d, Y',
        'valid_from' => 'date:M d, Y',
        'valid_to' => 'date:M d, Y',
        'terminated_at' => 'datetime:M d, Y, h:i A',
    ];

    public function client()
    {
        return $this->belongsTo(ClientMaster::class, 'client_id');
    }

    public function proposal()
    {
        return $this->belongsTo(ClientProposal::class, 'client_proposal_id');
    }

    public function rates()
    {
        return $this->hasMany(ClientContractRate::class, 'contract_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function terminator()
    {
        return $this->belongsTo(User::class, 'terminated_by');
    }

    /**
     * Team-scoped visibility, same rule as CRM leads/Proposals/Clients. A
     * contract always has a client (creation is guarded on it), so this just
     * delegates to ClientMaster::scopeVisibleTo() via that relation rather
     * than re-deriving the lead fallback chain through the proposal.
     */
    public function scopeVisibleTo($query, ?array $userIds)
    {
        if ($userIds === null) {
            return $query;
        }

        return $query->whereHas('client', fn ($q) => $q->visibleTo($userIds));
    }
}
