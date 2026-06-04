<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmStatus extends Model
{
    protected $table = 'crm_status';

    protected $fillable = [
        'status',
        'description'
    ];
}
