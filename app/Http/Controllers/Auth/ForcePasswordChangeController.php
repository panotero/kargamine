<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ForcePasswordChangeController extends Controller
{
    /**
     * Show the set-new-password form, shown instead of the app to any
     * user whose account is flagged must_change_password (set when an
     * admin creates their account).
     */
    public function create(): View
    {
        return view('auth.force-password-change');
    }

    /**
     * Set the user's new password and clear the flag, then send them into
     * the app.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = $request->user();
        $user->password = Hash::make($validated['password']);
        $user->must_change_password = false;
        $user->save();

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
