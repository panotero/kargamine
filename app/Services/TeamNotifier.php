<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Pairs an in-app Notification with a queued email, for the team-hierarchy
 * notification flows (new CRM lead -> team leader, proposal decision ->
 * creator, etc). Shared so every caller sends the same "notification row +
 * email" pair the same way, matching the existing pattern used elsewhere in
 * the app (e.g. RoutingController) but through the newer Notification /
 * NotificationService API instead of a raw DB::table() insert.
 */
class TeamNotifier
{
    /**
     * Direct team leaders of $actor's own team - no cascade upward, and
     * excluding $actor themselves if they happen to be one of the leaders
     * (a co-leader shouldn't be notified about their own action).
     *
     * @return int[]
     */
    public static function directLeaderIds(User $actor): array
    {
        if (!$actor->team_id) {
            return [];
        }

        return User::where('team_id', $actor->team_id)
            ->where('is_team_leader', true)
            ->where('id', '!=', $actor->id)
            ->pluck('id')
            ->all();
    }

    /**
     * Send a Notification (single batch insert) + one queued email per
     * recipient. No-ops quietly if $userIds ends up empty (e.g. actor has no
     * team, or their team has no other leader).
     *
     * @param  int[]  $userIds
     * @param  array{
     *     title: string,
     *     message: string,
     *     type?: string,
     *     from_user_id?: int,
     *     notifiable?: Model,
     *     link?: array{title?: string, url?: string},
     *     email_subject?: string,
     *     data?: array,
     * }  $payload
     */
    public static function notify(array $userIds, array $payload): void
    {
        $userIds = array_values(array_unique(array_filter($userIds)));

        if (!$userIds) {
            return;
        }

        NotificationService::send([
            'type' => $payload['type'] ?? null,
            'title' => $payload['title'],
            'message' => $payload['message'],
            'from_user_id' => $payload['from_user_id'] ?? null,
            'notifiable' => $payload['notifiable'] ?? null,
            'link' => $payload['link'] ?? [],
            'data' => $payload['data'] ?? null,
            'target' => ['type' => 'users', 'user_ids' => $userIds],
        ]);

        $mailer = app(ApplicationMailer::class);
        $link = $payload['link'] ?? [];

        foreach ($userIds as $userId) {
            $mailer->send([
                'subject' => $payload['email_subject'] ?? $payload['title'],
                'title' => $payload['title'],
                'message' => $payload['message'],
                'button' => isset($link['url']) ? [
                    'url' => url($link['url']),
                    'text' => $link['title'] ?? 'View',
                ] : null,
            ], $userId);
        }
    }
}
