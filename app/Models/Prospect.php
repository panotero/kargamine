<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Prospect extends Model
{
    use HasUuids;

    public const CONTAINER_TYPES = ['CV', 'FR', 'RF', 'LC', 'RC'];

    public const GENDERS = ['Male', 'Female'];

    protected $table = 'prospects';

    protected $fillable = [
        'title',
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'email',
        'email_type',
        'position',
        'mobile',
        'mobile_type',
        'landline_number',
        'landline_type',
        'client_type',
        'customer_code',
        'status',
        'source',
        'assigned_to',
        'estimated_value',
        'expected_close_date',
        'status_updated_at',
        'requires_proposal',
        'relationship_manager_id',
    ];

    protected $appends = ['contact_name', 'is_fully_assigned'];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'requires_proposal' => 'boolean',
    ];

    public function containers()
    {
        return $this->hasMany(ProspectContainerLegacy::class, 'prospect_id');
    }

    public function addresses()
    {
        return $this->hasMany(ProspectLocation::class, 'prospect_id');
    }

    public function clientMaster()
    {
        return $this->hasOne(ClientMaster::class, 'prospect_id');
    }

    public function contacts()
    {
        return $this->hasMany(ProspectContact::class, 'prospect_id');
    }

    public function proposalRequests()
    {
        return $this->hasMany(ProposalRequest::class, 'prospect_id');
    }

    /**
     * Convenience "currently being worked on" accessor - a prospect can have
     * several Proposal Requests over time (pending -> assigned -> for_approval
     * -> approved -> signed, or cancelled), each started fresh via the
     * "Request for Proposal" button. Call sites still using
     * this singular relation (the wizard's own companyDetails/signatories/
     * Origin & Destination endpoints when no explicit id is known yet,
     * ProspectInfoModal's own Origin & Destination tab/
     * AddOriginDestinationModal) get "whichever request is currently being
     * drafted." The original Requirements tab does NOT use this at all
     * anymore - Freight/Trucking/Charter rows are owned directly by the
     * prospect (see requirementContainers()/etc. below), independent of
     * any Proposal Request.
     */
    public function proposalRequest()
    {
        return $this->hasOne(ProposalRequest::class, 'prospect_id')
            ->whereIn('status', ['pending', 'assigned'])
            ->latestOfMany();
    }

    /**
     * "Prospect products" - the original Requirements tab's Freight/
     * Trucking/Charter rows. Owned directly by the prospect (prospect_id),
     * NOT by any Proposal Request - having these does not mean a proposal
     * has been requested, and they must never be affected by creating,
     * submitting, or deleting a Proposal Request.
     */
    public function requirementContainers()
    {
        return $this->hasMany(ProposalRequestContainer::class, 'prospect_id');
    }

    public function requirementTruckings()
    {
        return $this->hasMany(ProposalRequestTrucking::class, 'prospect_id');
    }

    public function requirementCharters()
    {
        return $this->hasMany(ProposalRequestCharter::class, 'prospect_id');
    }

    public function relationshipManager()
    {
        return $this->belongsTo(User::class, 'relationship_manager_id');
    }

    /**
     * Both a CSR (assigned_to) and a Relationship Manager
     * (relationship_manager_id) must be set by Management before the RFP
     * wizard endpoints unlock - see AuthorizesProposalRequestAccess.
     */
    public function isFullyAssigned(): bool
    {
        return $this->assigned_to !== null && $this->relationship_manager_id !== null;
    }

    public function getIsFullyAssignedAttribute(): bool
    {
        return $this->isFullyAssigned();
    }

    /**
     * Computed, read-only - title/first/middle/last were split out of what
     * used to be one contact_name column. Kept as an appended attribute
     * (not a real column) so every existing display/search/notification
     * that reads $lead->contact_name or lead.contact_name in JSON keeps
     * working unchanged.
     */
    public function getContactNameAttribute(): string
    {
        return trim(collect([$this->title, $this->first_name, $this->middle_name, $this->last_name])
            ->filter()
            ->implode(' ')) ?: '-';
    }

    /**
     * Best-effort split of a single free-text name into first/middle/last -
     * only used by the couple of legacy endpoints (store()/update()) that
     * still accept one contact_name field from an external caller instead
     * of the structured fields the current CRM form submits.
     *
     * @return array{first_name: ?string, middle_name: ?string, last_name: ?string}
     */
    public static function splitFullName(?string $name): array
    {
        $parts = preg_split('/\s+/', trim((string) $name), -1, PREG_SPLIT_NO_EMPTY);

        if (empty($parts)) {
            return ['first_name' => null, 'middle_name' => null, 'last_name' => null];
        }

        $first = array_shift($parts);
        $last = count($parts) ? array_pop($parts) : null;
        $middle = $parts ? implode(' ', $parts) : null;

        return ['first_name' => $first, 'middle_name' => $middle, 'last_name' => $last];
    }

    /**
     * An address only needs type/province/town-city to count as complete -
     * not every PSGC-cascade sub-field. whereNotNull() alone isn't enough
     * here since an empty string passes it (unlike missingRequirements()'s
     * empty() check below) - guard both so the two stay symmetric.
     */
    private static function requireNonEmptyColumns($query, array $columns)
    {
        foreach ($columns as $column) {
            $query->whereNotNull($column)->where($column, '!=', '');
        }

        return $query;
    }

    public function stageCompletionFlags(): array
    {
        $company = $this->company;
        $isCorporate = $this->client_type === 'corporate';

        $hasCompleteAddress = self::requireNonEmptyColumns(
            $this->addresses(),
            ['address_type', 'address_province', 'address_town_city']
        )->exists();

        // Gate 1 ("Identity complete") mirrors the new Identity tab only:
        // Source, Prospect/Company name, Business type, Relationship
        // Manager, and Locations. Contact-person name/mobile and the
        // authorized-signatory block moved to the separate Contacts tab
        // (gate 2) and are no longer collected here, so they're no longer
        // part of this gate.
        $stage1 = (bool) (
            $this->source && $this->client_type &&
            $company && $company->company_name && $company->type_of_business &&
            $hasCompleteAddress
        );

        return [
            1 => $stage1,
            2 => $this->contacts()->exists(),
            // "Requirements complete" = at least one requirement of ANY
            // product (Container/Trucking/Charter) has been added - owned
            // directly by the prospect, independent of any Proposal
            // Request (see requirementContainers()/etc. above).
            3 => (bool) (
                $this->requirementContainers()->exists()
                || $this->requirementTruckings()->exists()
                || $this->requirementCharters()->exists()
            ),
        ];
    }

    /**
     * Human-readable list of what's still missing before this lead can be
     * promoted to OPPORTUNITY - so a caller can tell the user exactly why
     * it wasn't moved, instead of a blanket "saved" with no explanation.
     */
    public function missingRequirements(): array
    {
        $company = $this->company;
        $isCorporate = $this->client_type === 'corporate';
        $missing = [];

        if (! $this->source) {
            $missing[] = 'Lead Source';
        }

        if (! $company || ! $company->company_name) {
            $missing[] = $isCorporate ? 'Company Name' : 'Account Name';
        }

        if (! $company || ! $company->type_of_business) {
            $missing[] = 'Business Type';
        }

        $addressFieldLabels = [
            'address_type' => 'Address Type',
            'address_province' => 'Address Province',
            'address_town_city' => 'Address Town/City',
        ];

        $address = $this->addresses->firstWhere('is_primary', true) ?? $this->addresses->first();

        if (! $address) {
            $missing[] = 'At least one Address';
        } else {
            foreach ($addressFieldLabels as $field => $label) {
                if (empty($address->$field)) {
                    $missing[] = $label;
                }
            }
        }

        if (! $this->contacts()->exists()) {
            $missing[] = 'At least one Contact Person';
        }

        $hasAnyRequirement = $this->requirementContainers()->exists()
            || $this->requirementTruckings()->exists()
            || $this->requirementCharters()->exists();
        if (! $hasAnyRequirement) {
            $missing[] = 'At least one Requirement';
        }

        return $missing;
    }

    /**
     * @return bool whether this call actually promoted the lead to
     *              OPPORTUNITY (false if it was already complete/beyond,
     *              or still incomplete) - lets a caller report the real
     *              outcome instead of assuming it always happens.
     */
    public function recomputeCompletion(): bool
    {
        $flags = $this->stageCompletionFlags();
        $this->is_complete = ! in_array(false, $flags, true);
        $promoted = false;

        // Once both stages are done, promote the lead to OPPORTUNITY -
        // but never move it backward if it's already further along
        // (NEGOTIATION/WIN/LOST).
        if ($this->is_complete) {
            $opportunity = CrmStatus::where('status', 'OPPORTUNITY')->first();
            $lead = CrmStatus::where('status', 'PROSPECT')->first();
            $qualified = CrmStatus::where('status', 'QUALIFIED')->first();

            if ($opportunity && in_array($this->status, [$lead?->id, $qualified?->id, null], true)) {
                $this->status = $opportunity->id;
                $this->status_updated_at = now();
                $promoted = true;
            }
        }

        $this->save();

        return $promoted;
    }

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function company()
    {
        return $this->hasOne(ProspectCompanyInfo::class, 'prospect_id', 'id');
    }

    public function notes()
    {
        return $this->hasMany(ProspectNote::class, 'prospect_id')->orderBy('created_at', 'desc');
    }

    public function activities()
    {
        return $this->hasMany(ProspectActivity::class, 'prospect_id')->orderBy('created_at', 'desc');
    }

    public function crmStatus()
    {
        return $this->hasOne(CrmStatus::class, 'id', 'status');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'assigned_to');
    }

    public function proposals()
    {
        return $this->hasMany(Proposal::class, 'prospect_id');
    }

    public function clientProposals()
    {
        return $this->hasMany(ClientProposal::class, 'prospect_id')->latest();
    }

    public function hasAcceptedProposal(): bool
    {
        return $this->clientProposals()->where('status', ClientProposal::STATUS_ACCEPTED)->exists();
    }

    /**
     * Gate for the "Create Client Master" action - leads flagged
     * requires_proposal need an accepted Proposal first; leads that don't
     * require one (requires_proposal = false) can convert straight away.
     */
    public function canConvertToClient(): bool
    {
        return ! $this->requires_proposal || $this->hasAcceptedProposal();
    }

    public function formattedPrimaryAddress(): string
    {
        $address = $this->addresses->firstWhere('is_primary', true) ?? $this->addresses->first();

        if (! $address) {
            return '-';
        }

        return collect([
            $address->address_no,
            $address->address_building,
            $address->address_street,
            $address->address_barangay,
            $address->address_town_city,
            $address->address_province,
            $address->address_country,
            $address->address_postal_code,
        ])->filter()->implode(', ') ?: '-';
    }
}
