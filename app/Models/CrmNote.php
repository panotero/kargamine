<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmNote extends Model
{
    protected $table = 'crm_notes';
    protected $fillable = [
        'lead_id',
        'note',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
