<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->status === 1) {
                // Log the deactivated login attempt
                Log::warning('Deactivated user attempted access', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip' => $request->ip(),
                ]);

                Auth::logout(); // log them out
                return redirect()->route('login')->withErrors([
                    'email' => 'Your account is deactivated. Please contact admin.',
                ]);
            }
        }

        return $next($request);
    }
}
