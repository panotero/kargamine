<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesInfo extends Model
{
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $table = 'sales_info';

    protected $fillable = [
        'company_id',
        'account_owner',
    ];

    public function company()
    {
        return $this->belongsTo(CompanyInfoMaster::class, 'company_id');
    }
}
