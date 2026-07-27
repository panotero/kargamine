<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $casts = [
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
    ];

    protected $fillable = [
        'key',
        'label',
        'module',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(SettingRole::class, 'role_permission', 'permission_id', 'role_id');
    }
}
