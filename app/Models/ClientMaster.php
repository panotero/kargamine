<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ClientMaster extends Model
{
    use HasUuids;

    protected $fillable = [
        'customer_code',
        'company_name',
        'registered_address',
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

    public function uniqueIds(): array
    {
        return ['uuid'];
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
            1 => (bool) ($this->company_name && $this->registered_address && $this->contact_number_1),
            2 => $this->contacts()->exists(),
            3 => (bool) ($this->finance && $this->billing && $this->sales_rep_id),
        ];
    }

    public function recomputeCompletion(): void
    {
        $flags = $this->stageCompletionFlags();
        $this->is_complete = !in_array(false, $flags, true);
        $this->save();
    }
}
