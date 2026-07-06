<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDepartment extends Model
{
    use HasFactory;
    protected $table = 'user_department';
    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
    ];
}
