<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProposalRequest extends Model
{
    protected $fillable = [
        'code',
        'prospect_id',
        'status',
        'assigned_to',
        'relationship_manager_id',
        'created_by',
        'submitted_at',
        'submitted_by',
        'terms_of_payment_standard',
        'terms_of_payment_charter',
        'additional_notes',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    protected $appends = ['is_fully_assigned'];

    public function prospect()
    {
        return $this->belongsTo(Prospect::class, 'prospect_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    /**
     * The staffed CSR - Management's assignOwners() sets this on the
     * request itself (not the prospect) so staffing one request never
     * silently reassigns the prospect or any of its other requests. Named
     * user() (not assignedTo()) to match Prospect::user()'s existing
     * convention - lets the frontend keep reading `.user.name` either way.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function relationshipManager()
    {
        return $this->belongsTo(User::class, 'relationship_manager_id');
    }

    /**
     * Both a CSR and a Relationship Manager must be set by Management
     * before the RFP wizard endpoints unlock - see
     * AuthorizesProposalRequestAccess. Mirrors Prospect::isFullyAssigned(),
     * but scoped to this specific request now.
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
     * The commercial ClientProposal(s) built from this RFP. A ClientProposal
     * points back via proposal_request_id (see ClientProposal::proposalRequest()).
     */
    public function clientProposals()
    {
        return $this->hasMany(\App\Models\ClientProposal::class, 'proposal_request_id')->latest();
    }

    /**
     * The single most-recent ClientProposal built from this RFP. Normally
     * there is exactly one (submitProposalRequest() creates it atomically);
     * latestOfMany() picks the newest deterministically if more exist.
     */
    public function latestClientProposal()
    {
        return $this->hasOne(\App\Models\ClientProposal::class, 'proposal_request_id')->latestOfMany();
    }

    /**
     * Unifies this request's own lifecycle status with its latest
     * ClientProposal's status (once one exists) into a single value the
     * combined Requests/Approvals/Signed-Contracts list page filters and
     * displays by. Mirrors the table in the consolidation brief - request
     * 'cancelled' wins outright, then the ClientProposal's integer status
     * (if any) takes over, then the request's own pre-submission status
     * ('pending'/'assigned'), falling back to the raw request status for
     * any other (stale) value.
     */
    public function getEffectiveStatusAttribute(): string
    {
        if ($this->status === 'cancelled') {
            return 'cancelled';
        }

        $proposal = $this->latestClientProposal;

        if ($proposal) {
            return match ($proposal->status) {
                \App\Models\ClientProposal::STATUS_PENDING => 'pending_rm',
                \App\Models\ClientProposal::STATUS_PENDING_MANAGER => 'pending_manager',
                \App\Models\ClientProposal::STATUS_APPROVED => 'approved',
                \App\Models\ClientProposal::STATUS_ACCEPTED => 'accepted',
                \App\Models\ClientProposal::STATUS_DISAPPROVED => 'disapproved',
                \App\Models\ClientProposal::STATUS_REJECTED => 'rejected',
                \App\Models\ClientProposal::STATUS_CANCELLED => 'proposal_cancelled',
                default => $this->status,
            };
        }

        return match ($this->status) {
            'pending' => 'for_assignment',
            'assigned' => 'assigned',
            default => $this->status,
        };
    }

    public function companyDetail()
    {
        return $this->hasOne(ProposalRequestCompanyDetail::class, 'proposal_request_id');
    }

    public function signatories()
    {
        return $this->hasMany(ProposalRequestSignatory::class, 'proposal_request_id');
    }

    /**
     * The Origin & Destination tab's curated location list - relation name
     * kept short ("locations", not "originDestinationLocations") so it
     * serializes to a plain `locations` key, matching the sibling
     * containers()/truckings()/charters() relations' naming style.
     */
    public function locations()
    {
        return $this->hasMany(ProposalRequestLocation::class, 'proposal_request_id');
    }

    // ------------------------------------------------------------------
    // Products tab - a wholly parallel, richer set of product rows,
    // independent of containers()/truckings()/charters() above (which
    // stay the Requirements tab's simpler shape).
    // ------------------------------------------------------------------
    public function productContainers()
    {
        return $this->hasMany(ProposalRequestProductContainer::class, 'proposal_request_id');
    }

    public function productRollingCargo()
    {
        return $this->hasMany(ProposalRequestProductRollingCargo::class, 'proposal_request_id');
    }

    public function productLooseCargo()
    {
        return $this->hasMany(ProposalRequestProductLooseCargo::class, 'proposal_request_id');
    }

    public function productTruckings()
    {
        return $this->hasMany(ProposalRequestProductTrucking::class, 'proposal_request_id');
    }

    public function productCharters()
    {
        return $this->hasMany(ProposalRequestProductCharter::class, 'proposal_request_id');
    }

    /**
     * Rate Matrix tab - one row per priced product line across all five
     * product types, keyed by (product_type, product_id).
     */
    public function productRates()
    {
        return $this->hasMany(ProposalRequestProductRate::class, 'proposal_request_id');
    }

    /**
     * Terms & Conditions tab - ancillary fee grid, keyed by (section,
     * service_name).
     */
    public function ancillaryFees()
    {
        return $this->hasMany(ProposalRequestAncillaryFee::class, 'proposal_request_id');
    }

    public function termsOfPayment()
    {
        return $this->hasMany(ProposalRequestTermsOfPayment::class, 'proposal_request_id');
    }

    /**
     * Relation names needed for a full Proposal Request tree (every tab's
     * data) - shared by ProspectController::show() (prefixed
     * proposalRequest./proposalRequests.) and
     * ProposalRequestAssignmentController::mine() (used directly), so the
     * "what a full recap needs" list can never drift apart between callers.
     */
    public static function fullTreeEagerLoadSuffixes(): array
    {
        return [
            'companyDetail.prospectLocation',
            'signatories.prospectContact',
            'locations.prospectLocation',
            'productContainers.containerSize:id,size',
            'productContainers.deliveryType:delivery_type_id,code,name',
            'productContainers.originLocation',
            'productContainers.destinationLocation',
            'productContainers.originPort:port_id,location_id,name',
            'productContainers.destinationPort:port_id,location_id,name',
            'productContainers.ancillaryServices.cargoYard',
            'productContainers.ancillaryServices.specialCharge',
            'productRollingCargo.deliveryType:delivery_type_id,code,name',
            'productRollingCargo.originLocation',
            'productRollingCargo.destinationLocation',
            'productRollingCargo.originPort:port_id,location_id,name',
            'productRollingCargo.destinationPort:port_id,location_id,name',
            'productRollingCargo.ancillaryServices.cargoYard',
            'productRollingCargo.ancillaryServices.specialCharge',
            'productRollingCargo.topLoadCargo',
            'productLooseCargo.deliveryType:delivery_type_id,code,name',
            'productLooseCargo.originLocation',
            'productLooseCargo.destinationLocation',
            'productLooseCargo.originPort:port_id,location_id,name',
            'productLooseCargo.destinationPort:port_id,location_id,name',
            'productLooseCargo.ancillaryServices.cargoYard',
            'productLooseCargo.ancillaryServices.specialCharge',
            'productTruckings.originLocation',
            'productTruckings.destinationLocation',
            'productTruckings.ancillaryServices.cargoYard',
            'productTruckings.ancillaryServices.specialCharge',
            'productCharters.cargoItems',
            'productCharters.ports.port:port_id,location_id,name',
            'productCharters.ports.port.location:location_id,name',
            'productRates',
            'ancillaryFees',
            'termsOfPayment',
        ];
    }

    /**
     * RQ-YY-XXXXXX, resetting each 2-digit year. Mirrors
     * ClientMaster::generateNextCustomerCode()'s lock-then-scan-then-retry
     * shape.
     */
    public static function generateNextCode(): string
    {
        return DB::transaction(function () {
            $prefix = 'RQ-'.now()->format('y').'-';

            $seq = DB::table('proposal_requests')
                ->where('code', 'like', "{$prefix}%")
                ->lockForUpdate()
                ->pluck('code')
                ->map(fn ($code) => (int) substr($code, strlen($prefix)))
                ->max() ?? 0;

            do {
                $seq++;
                $candidate = $prefix.str_pad((string) $seq, 6, '0', STR_PAD_LEFT);
            } while (self::where('code', $candidate)->exists());

            return $candidate;
        });
    }
}
