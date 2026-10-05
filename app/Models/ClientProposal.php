<?php

namespace App\Models;

use App\Services\TeamService;
use App\Support\PermissionHelper;
use App\Support\RoleHelper;
use Illuminate\Database\Eloquent\Model;

class ClientProposal extends Model
{
    public const STATUS_PENDING = 1;
    public const STATUS_APPROVED = 2;
    public const STATUS_DISAPPROVED = 3;
    public const STATUS_ACCEPTED = 4;
    public const STATUS_REJECTED = 5;
    public const STATUS_CANCELLED = 6;
    public const STATUS_PENDING_MANAGER = 7;

    public const STATUS_LABELS = [
        self::STATUS_PENDING => 'Pending RM Approval',
        self::STATUS_APPROVED => 'Approved',
        self::STATUS_DISAPPROVED => 'Disapproved',
        self::STATUS_ACCEPTED => 'Accepted',
        self::STATUS_REJECTED => 'Rejected',
        self::STATUS_CANCELLED => 'Cancelled',
        self::STATUS_PENDING_MANAGER => 'Pending Manager Approval',
    ];

    protected $fillable = [
        'uuid',
        'code',
        'client_id',
        'prospect_id',
        'proposal_request_id',
        'status',
        'created_by',
        'signed_document_path',
        'signed_at',
        'signature_requested_at',
        'decided_by',
        'decided_at',
        'decision_remarks',
        'rm_approved_by',
        'rm_approved_at',
        'include_special_charges',
        'include_port_charges',
        'include_handling_fee',
        'include_general_charges',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'signed_at' => 'datetime:M d, Y, h:i A',
        'signature_requested_at' => 'datetime:M d, Y, h:i A',
        'decided_at' => 'datetime:M d, Y, h:i A',
        'rm_approved_at' => 'datetime:M d, Y, h:i A',
        'include_special_charges' => 'boolean',
        'include_port_charges' => 'boolean',
        'include_handling_fee' => 'boolean',
        'include_general_charges' => 'boolean',
    ];

    // Computed permission flags travel with every JSON response, so the
    // frontend never re-implements this logic - it just reads p.can_approve / p.can_reject / p.can_upload_signed.
    protected $appends = ['can_approve', 'can_reject', 'can_upload_signed', 'can_cancel'];

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
     * Approval is now a two-step, permission-gated chain instead of the old
     * team-leader pyramid: STATUS_PENDING is the Relationship Manager's
     * forward-only checkpoint (canBeApprovedByRm()), STATUS_PENDING_MANAGER
     * is the Manager's authoritative decision (canBeApprovedByManager()).
     * Dispatching on status here keeps canBeCancelledBy() and
     * getCanApproveAttribute() correct with no further changes.
     */
    public function canBeApprovedBy(?User $user): bool
    {
        return match ($this->status) {
            self::STATUS_PENDING => $this->canBeApprovedByRm($user),
            self::STATUS_PENDING_MANAGER => $this->canBeApprovedByManager($user),
            default => false,
        };
    }

    /**
     * Stage 1: must be *this deal's* assigned Relationship Manager (not just
     * anyone with the permission) - mirrors how the old check required being
     * within the owner's specific team, not just any team leader anywhere.
     */
    public function canBeApprovedByRm(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        if (RoleHelper::hasAnyRole($user, ['superadmin'])) {
            return true;
        }

        if (!PermissionHelper::userCan($user, 'proposal.approve.rm')) {
            return false;
        }

        $rm = $this->assignedRelationshipManager();

        return $rm && (int) $rm->id === (int) $user->id;
    }

    /**
     * Stage 2: the final, authoritative decision - not scoped to the deal,
     * same as how Management (superadmin/admin) isn't deal-specific today.
     */
    public function canBeApprovedByManager(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        if (RoleHelper::hasAnyRole($user, ['superadmin'])) {
            return true;
        }

        return PermissionHelper::userCan($user, 'proposal.approve.manager');
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

    /**
     * Cancelling is only meaningful while pending RM or Manager approval
     * (see the "cancel" action in ClientProposalController) - open to the
     * proposal's own creator, or whoever could approve it at its current
     * stage (canBeApprovedBy() is stage-aware), plus superadmin.
     */
    public function canBeCancelledBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        if ((int) $this->created_by === (int) $user->id) {
            return true;
        }

        return $this->canBeApprovedBy($user);
    }

    public function getCanCancelAttribute(): bool
    {
        return $this->canBeCancelledBy(auth()->user());
    }

    public function prospect()
    {
        return $this->belongsTo(Prospect::class, 'prospect_id');
    }

    /**
     * The ProposalRequest (RFP) this commercial proposal was built from, if
     * any - lets approve()/attachSigned() sync "approved"/"signed" onto the
     * request's own status. Null for a ClientProposal created without one.
     */
    public function proposalRequest()
    {
        return $this->belongsTo(ProposalRequest::class, 'proposal_request_id');
    }

    /**
     * The user this proposal "belongs to" for team-scoping purposes: its own
     * lead's assigned rep, or - for proposals created via the client-scoped
     * store() path, which never sets prospect_id - the client's originating
     * lead's assigned rep instead.
     */
    public function ownerUser(): ?User
    {
        return $this->prospect?->user ?? $this->client?->prospect?->user;
    }

    /**
     * The deal's assigned Relationship Manager - same fallback chain as
     * ownerUser() (this proposal's own prospect first, then the client's
     * originating prospect for proposals created via the client-scoped
     * store() path). This is who gives the first (RM) approval.
     */
    public function assignedRelationshipManager(): ?User
    {
        return $this->prospect?->relationshipManager ?? $this->client?->prospect?->relationshipManager;
    }

    /**
     * The deal's authorized signatory (name + email) - captured on the
     * originating lead's company info at intake, same fallback chain as
     * ownerUser(): this proposal's own lead first, then the client's
     * originating lead for proposals created via the client-scoped store()
     * path (which never sets prospect_id).
     */
    public function authorizedSignatoryContact(): ?array
    {
        $company = $this->prospect?->company ?? $this->client?->prospect?->company;

        if (! $company || ! $company->authorized_signatory_email) {
            return null;
        }

        return [
            'name' => $company->authorized_signatory_name ?: $company->authorized_signatory_email,
            'email' => $company->authorized_signatory_email,
        ];
    }
}
