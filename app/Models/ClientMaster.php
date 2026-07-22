<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ClientMaster extends Model
{
    use HasUuids;

    protected $fillable = [
        'customer_code',
        'company_name',
        'contact_number_1',
        'contact_number_2',
        'industry',
        'organization_type',
        'tin',
        'business_start_date',
        'estimated_annual_revenue',
        'company_url',
        'sales_rep_id',
        'current_stage',
        'is_complete',
        'created_by',
    ];

    protected $casts = [
        'business_start_date' => 'date',
        'estimated_annual_revenue' => 'decimal:2',
        'is_complete' => 'boolean',
    ];

    public function lead()
    {
        return $this->belongsTo(\App\Models\CrmLead::class, 'lead_id');
    }

    public function addresses()
    {
        return $this->hasMany(ClientAddress::class, 'client_id');
    }

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    /**
     * CM-{year}-0001, resetting each year. Finds the current highest
     * sequence for this year across BOTH client_masters and crm_leads
     * (a lead reserves its code here before a ClientMaster row exists),
     * then retries forward past any collision until a free one is found.
     */
    public static function generateNextCustomerCode(): string
    {
        return DB::transaction(function () {
            $prefix = 'CM-'.now()->format('Y').'-';

            $maxSequenceIn = function (string $table) use ($prefix) {
                return DB::table($table)
                    ->where('customer_code', 'like', "{$prefix}%")
                    ->lockForUpdate()
                    ->pluck('customer_code')
                    ->map(fn ($code) => (int) substr($code, strlen($prefix)))
                    ->max() ?? 0;
            };

            $seq = max($maxSequenceIn('client_masters'), $maxSequenceIn('crm_leads'));

            do {
                $seq++;
                $candidate = $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
            } while (
                self::where('customer_code', $candidate)->exists()
                || CrmLead::where('customer_code', $candidate)->exists()
            );

            return $candidate;
        });
    }

    public function contacts()
    {
        return $this->hasMany(ClientContact::class, 'client_id');
    }

    public function tradeReferences()
    {
        return $this->hasMany(ClientTradeReference::class, 'client_id');
    }

    public function finance()
    {
        return $this->hasOne(ClientFinance::class, 'client_id');
    }

    public function billing()
    {
        return $this->hasOne(ClientBilling::class, 'client_id');
    }

    public function salesRep()
    {
        return $this->belongsTo(User::class, 'sales_rep_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Required fields per stage - used to compute completion.
     */
    public function stageCompletionFlags(): array
    {
        return [
            1 => (bool) ($this->company_name && $this->contact_number_1 && $this->addresses()->exists()),
            2 => $this->contacts()->exists(),
            3 => (bool) ($this->finance && $this->billing && $this->sales_rep_id),
        ];
    }

    public function recomputeCompletion(): void
    {
        $flags = $this->stageCompletionFlags();
        $this->is_complete = ! in_array(false, $flags, true);
        $this->save();
    }

    /**
     * Not an accessor/append on purpose - calling this unconditionally via
     * $appends would trigger an addresses() lazy-load per row on the
     * paginated client list. Call explicitly (PDF templates, or
     * setAttribute() in a single-record show()) where it's actually needed.
     */
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
