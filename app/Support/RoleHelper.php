<?php

namespace App\Support;

use App\Models\User;

class RoleHelper
{
    public static function roleName(?User $user): ?string
    {
        if (!$user) {
            return null;
        }

        return $user->role?->role_name ? strtolower($user->role->role_name) : null;
    }

    public static function hasAnyRole(?User $user, array $roleNames): bool
    {
        $role = self::roleName($user);

        if (!$role) {
            return false;
        }

        return in_array($role, array_map('strtolower', $roleNames), true);
    }
}
