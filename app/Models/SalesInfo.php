<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesInfo extends Model
{
    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
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
