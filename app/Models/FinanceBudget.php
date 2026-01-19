<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceBudget extends Model
{
    use HasFactory;
    protected $table = 'finance_budget';

    protected $fillable = [
        'year',
        'amount',
    ];

    protected $casts = [
        'year'   => 'integer',
        'amount' => 'decimal:2',
    ];
}
