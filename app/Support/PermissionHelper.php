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

    /**
     * Every user whose role has been granted $key - for notifying "whoever
     * can approve this" when there's no single assigned individual to
     * notify (unlike a deal's specific Relationship Manager).
     */
    public static function userIdsWithPermission(string $key): array
    {
        return User::whereHas('role.permissions', fn ($q) => $q->where('key', $key))
            ->pluck('id')
            ->all();
    }
}
