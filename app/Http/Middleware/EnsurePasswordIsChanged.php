<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsurePasswordIsChanged
{
    /**
     * Force users flagged must_change_password (set on admin-created
     * accounts) to the set-password page before they can reach anything
     * else in the authenticated app.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->must_change_password && !$request->routeIs('password.force.*')) {
            return redirect()->route('password.force.create');
        }

        return $next($request);
    }
}
