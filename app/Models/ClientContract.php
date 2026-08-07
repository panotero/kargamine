<?php

namespace App\Models;

use App\Services\TeamService;
use App\Support\RoleHelper;
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
        'decided_by',
        'decided_at',
        'decision_remarks',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'signed_date' => 'date:M d, Y',
        'valid_from' => 'date:M d, Y',
        'valid_to' => 'date:M d, Y',
        'terminated_at' => 'datetime:M d, Y, h:i A',
        'decided_at' => 'datetime:M d, Y, h:i A',
    ];

    // Computed permission flag travels with every JSON response, so the
    // frontend never re-implements this logic - it just reads c.can_approve.
    protected $appends = ['can_approve'];

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

    public function decisionMaker()
    {
        return $this->belongsTo(User::class, 'decided_by');
    }

    /**
     * Approval follows the team hierarchy, mirroring
     * ClientProposal::canBeApprovedBy(): only the team leader over the
     * contract owner (their own team leader, or a leader further up the
     * pyramid) may approve - superadmin always can, regardless of team.
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

    public function getCanApproveAttribute(): bool
    {
        return $this->canBeApprovedBy(auth()->user());
    }

    /**
     * The user this contract "belongs to" for team-scoping purposes: its
     * client's assigned Sales Rep. A contract always has a client_id, so
     * this is simpler than ClientProposal's lead-fallback chain.
     */
    public function ownerUser(): ?User
    {
        return $this->client?->salesRep;
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
