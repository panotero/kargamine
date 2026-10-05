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
        'created_by',
        'submitted_at',
        'submitted_by',
    ];

    protected $casts = [
        'submitted_at' => 'datetime:M d, Y, h:i A',
    ];

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
     * The commercial ClientProposal(s) built from this RFP. A ClientProposal
     * points back via proposal_request_id (see ClientProposal::proposalRequest()).
     */
    public function clientProposals()
    {
        return $this->hasMany(\App\Models\ClientProposal::class, 'proposal_request_id')->latest();
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
