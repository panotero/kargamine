<?php

namespace App\Models;

use App\Services\TeamService;
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
        'lead_id',
        'status',
        'created_by',
        'signed_document_path',
        'signed_at',
        'decided_by',
        'decided_at',
        'decision_remarks',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'signed_at' => 'datetime:M d, Y, h:i A',
        'decided_at' => 'datetime:M d, Y, h:i A',
    ];

    // Computed permission flags travel with every JSON response, so the
    // frontend never re-implements this logic - it just reads p.can_approve / p.can_reject / p.can_upload_signed.
    protected $appends = ['can_approve', 'can_reject', 'can_upload_signed'];

    public function client()
    {
        return $this->belongsTo(ClientMaster::class, 'client_id');
    }

    public function rates()
    {
        return $this->hasMany(ClientProposalRate::class, 'proposal_id');
    }

    /**
     * The contract this proposal converted into, if any and still Active -
     * used to swap the Proposals page's "Create Contract" button to
     * "View Contract" once one already exists.
     */
    public function activeContract()
    {
        return $this->hasOne(ClientContract::class, 'client_proposal_id')
            ->where('status', ClientContract::STATUS_ACTIVE);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function decidedBy()
    {
        return $this->belongsTo(User::class, 'decided_by');
    }

    /**
     * Approval now follows the team hierarchy instead of the old
     * approver_roles config: only the team leader over the lead's assigned
     * rep (their own team leader, or a leader further up the pyramid) may
     * approve/disapprove - superadmin always can, regardless of team.
     */
    public function canBeApprovedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        if (RoleHelper::hasAnyRole($user, ['superadmin'])) {
            return true;
        }

        if (!$user->is_team_leader || !$user->team_id) {
            return false;
        }

        $ownerTeamId = $this->ownerUser()?->team_id;

        if (!$ownerTeamId) {
            return false;
        }

        return TeamService::accessibleTeamIds($user)->contains($ownerTeamId);
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

    /**
     * Uploading the signed document is a team-member action (leader
     * included): anyone within the lead's assigned rep's team, or within a
     * leader's accessible subtree that reaches that team, can upload it.
     */
    public function canBeSignedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        if (RoleHelper::hasAnyRole($user, ['superadmin'])) {
            return true;
        }

        $ownerTeamId = $this->ownerUser()?->team_id;

        if (!$ownerTeamId || !$user->team_id) {
            return false;
        }

        return TeamService::accessibleTeamIds($user)->contains($ownerTeamId);
    }

    public function getCanApproveAttribute(): bool
    {
        return $this->canBeApprovedBy(auth()->user());
    }

    public function getCanRejectAttribute(): bool
    {
        return $this->canBeRejectedBy(auth()->user());
    }

    public function getCanUploadSignedAttribute(): bool
    {
        return $this->canBeSignedBy(auth()->user());
    }
    public function lead()
    {
        return $this->belongsTo(CrmLead::class, 'lead_id');
    }

    /**
     * The user this proposal "belongs to" for team-scoping purposes: its own
     * lead's assigned rep, or - for proposals created via the client-scoped
     * store() path, which never sets lead_id - the client's originating
     * lead's assigned rep instead.
     */
    public function ownerUser(): ?User
    {
        return $this->lead?->user ?? $this->client?->lead?->user;
    }
}
