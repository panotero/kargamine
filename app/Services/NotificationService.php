<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class NotificationService
{
    /**
     * Send a notification to a user, role, or department.
     *
     * @param  array{
     *     type?: string,
     *     title: string,
     *     message: string,
     *     notifiable?: Model,
     *     from_user_id?: int,
     *     link?: array{title?: string, url?: string},
     *     data?: array,
     *     target: array{
     *         type: 'user'|'users'|'role'|'department',
     *         user_id?: int,
     *         user_ids?: int[],
     *         role?: string,
     *         department_id?: int,
     *     },
     * }  $payload
     * @return Collection<int, Notification>
     */
    public static function send(array $payload): Collection
    {
        $userIds = static::resolveTargetUserIds($payload['target'] ?? []);

        if ($userIds->isEmpty()) {
            return collect();
        }

        $notifiable = $payload['notifiable'] ?? null;
        $link = $payload['link'] ?? [];
        $now = now();

        $rows = $userIds->map(fn (int $userId) => [
            'type' => $payload['type'] ?? null,
            'notifiable_type' => $notifiable ? $notifiable->getMorphClass() : null,
            'notifiable_id' => $notifiable?->getKey(),
            'user_id' => $userId,
            'from_user_id' => $payload['from_user_id'] ?? null,
            'title' => $payload['title'],
            'message' => $payload['message'],
            'link_title' => $link['title'] ?? null,
            'link_url' => $link['url'] ?? null,
            'data' => isset($payload['data']) ? json_encode($payload['data']) : null,
            'is_read' => false,
            'created_at' => $now,
            'updated_at' => $now,
        ])->all();

        Notification::insert($rows);

        return Notification::whereIn('user_id', $userIds->all())
            ->where('created_at', $now)
            ->get();
    }

    /**
     * @return Collection<int, int>
     */
    protected static function resolveTargetUserIds(array $target): Collection
    {
        $type = $target['type'] ?? null;

        return match ($type) {
            'user' => collect([$target['user_id'] ?? null])->filter()->map(fn ($id) => (int) $id),
            'users' => collect($target['user_ids'] ?? [])->filter()->map(fn ($id) => (int) $id)->unique()->values(),
            'role' => static::resolveRoleUserIds($target['role'] ?? null),
            'department' => static::resolveDepartmentUserIds($target['department_id'] ?? null),
            default => throw new InvalidArgumentException("Invalid notification target type: {$type}"),
        };
    }

    protected static function resolveRoleUserIds(?string $roleName): Collection
    {
        if (!$roleName) {
            return collect();
        }

        return User::whereHas('role', function ($query) use ($roleName) {
            $query->whereRaw('LOWER(role_name) = ?', [strtolower($roleName)]);
        })->pluck('id');
    }

    protected static function resolveDepartmentUserIds(?int $departmentId): Collection
    {
        if (!$departmentId) {
            return collect();
        }

        return User::where('department_id', $departmentId)->pluck('id');
    }
}
