<?php

namespace App\Models;

use App\Support\RoleHelper;
use Illuminate\Database\Eloquent\Model;

class ClientProposal extends Model
{
    public const STATUS_PENDING = 1;
    public const STATUS_APPROVED = 2;
    public const STATUS_DISAPPROVED = 3;
    public const STATUS_ACCEPTED = 4;
    public const STATUS_REJECTED = 5;

    public const STATUS_LABELS = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_APPROVED => 'Approved',
        self::STATUS_DISAPPROVED => 'Disapproved',
        self::STATUS_ACCEPTED => 'Accepted',
        self::STATUS_REJECTED => 'Rejected',
    ];

    protected $fillable = [
        'uuid',
        'code',
        'client_id',
        'status',
        'created_by',
        'signed_document_path',
        'signed_at',
        'decided_by',
        'decided_at',
        'decision_remarks',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
        'decided_at' => 'datetime',
    ];

    // Computed permission flags travel with every JSON response, so the
    // frontend never re-implements this logic - it just reads p.can_approve / p.can_reject.
    protected $appends = ['can_approve', 'can_reject'];

    public function client()
    {
        return $this->belongsTo(ClientMaster::class, 'client_id');
    }

    public function rates()
    {
        return $this->hasMany(ClientProposalRate::class, 'proposal_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function decidedBy()
    {
        return $this->belongsTo(User::class, 'decided_by');
    }

    public function canBeApprovedBy(?User $user): bool
    {
        return RoleHelper::hasAnyRole($user, config('client_proposal_workflow.approver_roles', []));
    }

    public function canBeRejectedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        if (RoleHelper::hasAnyRole($user, config('client_proposal_workflow.reject_roles', []))) {
            return true;
        }

        // Fallback: the client's assigned Sales Rep acts as "account manager"
        // even before a dedicated role exists.
        return $this->client && (int) $this->client->sales_rep_id === (int) $user->id;
    }

    public function getCanApproveAttribute(): bool
    {
        return $this->canBeApprovedBy(auth()->user());
    }

    public function getCanRejectAttribute(): bool
    {
        return $this->canBeRejectedBy(auth()->user());
    }
}
