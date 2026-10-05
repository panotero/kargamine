<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * routes/page.php's controllers only ever return a bare content fragment -
 * the HTML window.loadPage() (resources/js/navmenu.js) fetches and drops
 * into #content. That's correct for the SPA's own AJAX navigation, but a
 * direct browser visit to one of these URLs (typing it in, opening a
 * shared link, a bookmark) never goes through loadPage() at all, so it got
 * that bare fragment with no sidebar/topbar/app shell around it.
 *
 * window.loadPage()'s own fetch always sends Accept: application/json to
 * ask for that bare fragment specifically - a real top-level browser
 * navigation sends Accept: text/html,... instead, so $request->wantsJson()
 * cleanly tells the two apart. For a direct visit, skip the page controller
 * entirely and render the same shell /app does, telling it (via
 * $directPageLink) which page to load into #content once the sidebar/menu
 * data has booted - see navmenu.js's initApp().
 */
class EnsureAppShellForDirectPageVisit
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->wantsJson()) {
            return $next($request);
        }

        return response()->view('dashboard', [
            'directPageLink' => '/' . ltrim($request->path(), '/'),
        ]);
    }
}
