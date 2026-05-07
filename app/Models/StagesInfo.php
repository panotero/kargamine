<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StagesInfo extends Model
{
    protected $table = 'stages_info';

    protected $fillable = [
        'company_id',
        'stage',
        'proposal_requested_date',
        'proposal_submitted_date',
        'negotiation_date',
        'won_awarded_date',
        'lost_closed_date',
        'monthly_sales_forecast',
        'forecast_transaction_month',
        'potential_volume_month',
        'remarks',
    ];

    protected $casts = [
        'proposal_requested_date' => 'date',
        'proposal_submitted_date' => 'date',
        'negotiation_date' => 'date',
        'won_awarded_date' => 'date',
        'lost_closed_date' => 'date',
        'forecast_transaction_month' => 'date',
        'monthly_sales_forecast' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(CompanyInfoMaster::class, 'company_id');
    }
}
