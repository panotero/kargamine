<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_name',
        'address',
        'description',
        'price',
        'link',
        'status',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
    ];
}
