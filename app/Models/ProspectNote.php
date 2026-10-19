<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProspectNote extends Model
{
    protected $table = 'prospect_notes';

    protected $fillable = [
        'prospect_id',
        'note',
        'created_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
