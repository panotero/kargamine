<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BilledDetail extends Model
{
    protected $table = 'billed_details';

    protected $fillable = [
        'company_id',
        'billed_to',
        'company_name',
        'address',
        'tin_no',
    ];

    public function company()
    {
        return $this->belongsTo(CompanyInfoMaster::class, 'company_id');
    }
}
