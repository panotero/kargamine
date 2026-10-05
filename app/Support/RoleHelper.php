<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\DB;

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

    /**
     * Ids of all Users whose role's role_name (case-insensitive) is in
     * $roleNames - e.g. RoleHelper::userIdsWithAnyRole(['superadmin', 'admin'])
     * defines "Management" for notification/assignment purposes.
     *
     * @return int[]
     */
    public static function userIdsWithAnyRole(array $roleNames): array
    {
        return User::whereHas('role', fn ($q) => $q->whereIn(
            DB::raw('LOWER(role_name)'),
            array_map('strtolower', $roleNames)
        ))->pluck('id')->all();
    }
}
