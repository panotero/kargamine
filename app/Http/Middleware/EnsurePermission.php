<?php

namespace App\Http\Middleware;

use App\Support\PermissionHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gates a route by permission key (e.g. `permission:booking.confirm`)
 * instead of a hardcoded role name - see App\Support\PermissionHelper
 * and the role_permission pivot for how a role earns a given key.
 */
class EnsurePermission
{
    public function handle(Request $request, Closure $next, string $key): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        if (! PermissionHelper::userCan($user, $key)) {
            abort(403, "You don't have the '{$key}' permission.");
        }

        return $next($request);
    }
}
