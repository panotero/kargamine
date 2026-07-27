<?php

namespace App\Support;

use App\Models\User;

class PermissionHelper
{
    public static function userCan(?User $user, string $key): bool
    {
        if (! $user || ! $user->role_id) {
            return false;
        }

        return $user->role?->permissions()->where('key', $key)->exists() ?? false;
    }
}
