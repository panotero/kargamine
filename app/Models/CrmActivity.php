<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmActivity extends Model
{
    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
    ];

    protected $table = 'crm_activities';

    protected $fillable = [
        'lead_id',
        'type',
        'description',
        'attachment',
        'created_by',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
