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
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'rate_percent' => 'decimal:2',
        'effective_date' => 'date:M d, Y',
        'end_date' => 'date:M d, Y',
        'is_active' => 'boolean',
    ];
}
