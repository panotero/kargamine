<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    use HasFactory;

    // Table name (optional if it matches the plural of the model)
    protected $table = 'finance';

    // Mass assignable fields
    protected $fillable = [
        'transaction',
        'date_processed',
        'uploading_office',
        'uploaded_by',
        'payee',
        'particular',
        'responsibility_center',
        'expenditure',
        'uacs_object_code',
        'amount',
        'fund_cluster',
        'date_signed',
        'file_name',
        'file_path',
    ];
}
