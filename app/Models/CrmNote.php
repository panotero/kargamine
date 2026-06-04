<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmNote extends Model
{
    protected $fillable = [
        'lead_id',
        'note',
        'created_by'
    ];
}
