<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Token-authenticated (Sanctum) device integration endpoints -
        // no browser session/cookie involved, so there's no CSRF token to
        // check. See routes/api.php's `device/v1` group.
        'api/device/*',
    ];
}
