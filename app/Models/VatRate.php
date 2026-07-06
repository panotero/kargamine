<?php

namespace App\Models;

use App\Traits\HasEffectivePeriod;
use Illuminate\Database\Eloquent\Model;

class VatRate extends Model
{
    use HasEffectivePeriod;

    protected $primaryKey = 'vat_rate_id';

    protected $fillable = ['rate_percent', 'effective_date', 'end_date', 'is_active'];

    protected $casts = [
        'rate_percent' => 'decimal:2',
        'effective_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];
}
