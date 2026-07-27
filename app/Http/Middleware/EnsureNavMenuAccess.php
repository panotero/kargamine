<?php

namespace App\Http\Middleware;

use App\Models\NavMenu;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gates a route by the same allowed_roles used to decide whether the
 * corresponding entry shows up in the sidebar (nav_menus.allowed_roles) -
 * one source of truth for "who can see this page" instead of a second,
 * separately-maintained role list per feature.
 */
class EnsureNavMenuAccess
{
    public function handle(Request $request, Closure $next, string $link): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        $menu = NavMenu::where('link', $link)->first();

        if (! $menu) {
            abort(403, 'This feature has no nav menu entry to check access against.');
        }

        $allowedRoles = json_decode($menu->allowed_roles ?? '[]', true) ?? [];

        if (! in_array((string) $user->role_id, $allowedRoles, true)) {
            abort(403, 'You do not have access to manage this resource.');
        }

        return $next($request);
    }
}
