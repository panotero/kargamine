<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavMenu extends Model
{
    protected $table = 'nav_menus';
    protected $fillable = [
        'title',
        'icon',
        'link',
        'allowed_roles',
        'allowed_office',
        'parent_menu',
    ];
}
