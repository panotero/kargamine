<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmActivity extends Model
{
    protected $table = 'crm_activities';
    protected $fillable = [
        'lead_id',
        'type',
        'description',
        'created_by'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
