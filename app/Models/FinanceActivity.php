<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceActivity extends Model
{
    use HasFactory;
    protected $table = 'finance_activity';

    protected $fillable = [
        'timestamp',
        'activity',
        'remarks',
        'status',
        'finance_id',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];
}
