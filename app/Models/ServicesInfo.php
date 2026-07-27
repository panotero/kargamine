<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicesInfo extends Model
{
    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
    ];

    protected $table = 'services_info';

    protected $fillable = [
        'company_id',
        'product',
        'origin',
        'destination',
    ];

    public function company()
    {
        return $this->belongsTo(CompanyInfoMaster::class, 'company_id');
    }
}
