<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class CrmLead extends Model
{
    use HasUuids;
    public const CONTAINER_TYPES = ['CV', 'FR', 'RF', 'LC', 'RC'];
    protected $table = 'crm_leads';
    protected $fillable = [
        'contact_name',
        'email',
        'position',
        'mobile',
        'status',
        'source',
        'assigned_to',
        'estimated_value',
        'expected_close_date',
        'status_updated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s A',
        'updated_at' => 'datetime:Y-m-d h:i:s A',
    ];
    public function containers()
    {
        return $this->hasMany(CrmLeadContainer::class, 'lead_id');
    }

    public function stageCompletionFlags(): array
    {
        $company = $this->company;

        $stage1 = (bool) (
            $this->contact_name && $this->mobile && $this->source &&
            $company && $company->company_name  &&
            $company->address_no && $company->address_building && $company->address_street &&
            $company->address_barangay && $company->address_town_city &&
            $company->address_province && $company->address_country &&
            $company->address_postal_code && $company->type_of_business
        );

        return [
            1 => $stage1,
            2 => $this->containers()->exists(),
        ];
    }

    public function recomputeCompletion(): void
    {
        $flags = $this->stageCompletionFlags();
        $this->is_complete = !in_array(false, $flags, true);

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
            }
        }

        $this->save();
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
        return $this->hasMany(Proposal::class,  'lead_id');
    }
}
