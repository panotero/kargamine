<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CrmLead extends Model
{
    use HasUuids;

    public const CONTAINER_TYPES = ['CV', 'FR', 'RF', 'LC', 'RC'];

    public const GENDERS = ['Male', 'Female', 'Rather not say'];

    protected $table = 'crm_leads';

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
    ];

    protected $appends = ['contact_name'];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
    ];

    public function containers()
    {
        return $this->hasMany(CrmLeadContainer::class, 'lead_id');
    }

    public function addresses()
    {
        return $this->hasMany(CrmLeadAddress::class, 'lead_id');
    }

    public function clientMaster()
    {
        return $this->hasOne(ClientMaster::class, 'lead_id');
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

        $hasAuthorizedSignatory = $company
            && $company->authorized_signatory_title
            && $company->authorized_signatory_first_name
            && $company->authorized_signatory_last_name
            && $company->authorized_signatory_position
            && $company->authorized_signatory_email
            && $company->authorized_signatory_email_type;

        $stage1 = (bool) (
            $this->first_name && $this->last_name && $this->mobile && $this->mobile_type &&
            $this->source && $this->client_type &&
            $company && $company->company_name &&
            (! $isCorporate || $company->type_of_business) &&
            (! $isCorporate || $hasAuthorizedSignatory) &&
            $hasCompleteAddress
        );

        return [
            1 => $stage1,
            2 => $this->containers()->exists(),
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

        if (! $this->first_name || ! $this->last_name) {
            $missing[] = 'Contact Name (First & Last)';
        }
        if (! $this->mobile) {
            $missing[] = 'Mobile Number';
        }
        if (! $this->mobile_type) {
            $missing[] = 'Mobile Number Type';
        }
        if (! $this->source) {
            $missing[] = 'Lead Source';
        }

        if (! $company || ! $company->company_name) {
            $missing[] = $isCorporate ? 'Company Name' : 'Account Name';
        }

        if ($isCorporate) {
            if (! $company || ! $company->type_of_business) {
                $missing[] = 'Type of Business';
            }
            if (
                ! $company
                || ! $company->authorized_signatory_title
                || ! $company->authorized_signatory_first_name
                || ! $company->authorized_signatory_last_name
                || ! $company->authorized_signatory_position
            ) {
                $missing[] = 'Authorized Signatory (Title, First Name, Last Name & Position)';
            }
            if (! $company || ! $company->authorized_signatory_email || ! $company->authorized_signatory_email_type) {
                $missing[] = 'Authorized Signatory Email & Email Type';
            }
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

        if (! $this->containers()->exists()) {
            $missing[] = 'At least one Booking Requirement';
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
            $lead = CrmStatus::where('status', 'LEAD')->first();
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
        return $this->hasOne(CrmCompanyInfo::class, 'lead_id', 'id');
    }

    public function notes()
    {
        return $this->hasMany(CrmNote::class, 'lead_id')->orderBy('created_at', 'desc');
    }

    public function activities()
    {
        return $this->hasMany(CrmActivity::class, 'lead_id')->orderBy('created_at', 'desc');
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
        return $this->hasMany(Proposal::class, 'lead_id');
    }

    public function clientProposals()
    {
        return $this->hasMany(ClientProposal::class, 'lead_id')->latest();
    }

    public function hasAcceptedProposal(): bool
    {
        return $this->clientProposals()->where('status', ClientProposal::STATUS_ACCEPTED)->exists();
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
