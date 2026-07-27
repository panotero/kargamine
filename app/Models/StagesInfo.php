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
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'proposal_requested_date' => 'date:M d, Y',
        'proposal_submitted_date' => 'date:M d, Y',
        'negotiation_date' => 'date:M d, Y',
        'won_awarded_date' => 'date:M d, Y',
        'lost_closed_date' => 'date:M d, Y',
        'forecast_transaction_month' => 'date:M d, Y',
        'monthly_sales_forecast' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(CompanyInfoMaster::class, 'company_id');
    }
}
