<?php

namespace App\Services;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Collection;

class TeamService
{
    /**
     * Team ids reachable below (and including) $teamId, walked in-memory
     * rather than via a DB-specific recursive CTE.
     */
    public static function descendantTeamIds(int $teamId, bool $includeSelf = true): Collection
    {
        $childrenMap = Team::all(['id', 'parent_id'])->groupBy('parent_id');

        $result = collect();
        $queue = [$teamId];

        while ($queue) {
            $currentId = array_shift($queue);
            $result->push($currentId);

            foreach ($childrenMap->get($currentId, collect()) as $child) {
                $queue[] = $child->id;
            }
        }

        if (!$includeSelf) {
            $result = $result->reject(fn ($id) => $id === $teamId);
        }

        return $result->values();
    }

    /**
     * Whether $teamId sits anywhere in $subtreeRootId's subtree (including being
     * $subtreeRootId itself). Used to reject a parent_id change that would create a cycle.
     */
    public static function isWithinSubtree(int $teamId, int $subtreeRootId): bool
    {
        return static::descendantTeamIds($subtreeRootId)->contains($teamId);
    }

    /**
     * Team ids this user can see data for: their own team plus every descendant
     * team if they're a leader, or just their own team otherwise.
     */
    public static function accessibleTeamIds(User $user): Collection
    {
        if (!$user->team_id) {
            return collect();
        }

        if ($user->is_team_leader) {
            return static::descendantTeamIds($user->team_id);
        }

        return collect([$user->team_id]);
    }

    /**
     * User ids this user can see data for: everyone in their team's subtree if
     * they're a leader, or just themselves otherwise.
     */
    public static function accessibleUserIds(User $user): Collection
    {
        if (!$user->team_id || !$user->is_team_leader) {
            return collect([$user->id]);
        }

        return User::whereIn('team_id', static::descendantTeamIds($user->team_id))->pluck('id');
    }
}
