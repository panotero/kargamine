<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProspectActivity extends Model
{
    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
    ];

    protected $table = 'prospect_activities';

    protected $fillable = [
        'prospect_id',
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
