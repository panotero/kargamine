<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavMenu extends Model
{
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $table = 'nav_menus';
    // App\Models\NavMenu.php

    protected $fillable = [
        'title',
        'category',
        'icon',
        'link',
        'allowed_roles',
        'parent_menu',
        'menu_order',
    ];
}
