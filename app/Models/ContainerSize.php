<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContainerSize extends Model
{
    protected $table = 'container_size';
    protected $fillable = ['size'];
    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
    ];
}
