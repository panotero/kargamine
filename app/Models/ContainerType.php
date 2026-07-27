<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContainerType extends Model
{
    protected $table = 'container_type';
    protected $fillable = ['type'];
    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
    ];
}
