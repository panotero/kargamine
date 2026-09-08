---
name: project-nav-access-settings-gap
description: nav_menus has no row for /page_settings, so any route gated by nav.access:/page_settings 403s for everyone including superadmin until that row is seeded.
metadata:
  type: project
---

`App\Http\Middleware\EnsureNavMenuAccess` (aliased `nav.access`) looks up `NavMenu::where('link', $link)->first()` and hard-aborts 403 ("This feature has no nav menu entry to check access against.") if no row exists for that link — there is **no superadmin bypass** in this middleware (unlike `TeamService`/role-list checks elsewhere in the app).

As of 2026-09-06, `nav_menus` has zero rows for any `/page_settings`-related link. Confirmed via `NavMenu::where('link','LIKE','%settings%')->get()` returning `[]`, and via a live HTTP request as the seeded superadmin returning a 403 with that exact message.

**Why:** This surfaced while wiring `->middleware('nav.access:/page_settings')` onto new offices/documenttypes/labeltypes/userconfigs mutating routes (Master Data settings page backend, 2026-09-06) per an explicit brief instruction to mirror the existing `container-assets`/`page_maintenance` pattern. The routes are correct; the *data* needed for the gate to open (a `nav_menus` row with `link = '/page_settings'` and the relevant `role_id`s in its `allowed_roles` JSON) doesn't exist yet.

**How to apply:** If asked to debug "why does POST/DELETE on offices/documenttypes/labeltypes/userconfigs 403 for everyone," check for a `nav_menus` row with `link = '/page_settings'` before suspecting the route/controller/middleware code — the fix is almost certainly seeding that row (whoever owns nav menu config / the Settings page sidebar entry), not a code change. Same class of gap likely applies to any other page whose nav.access middleware references a link with no corresponding sidebar entry yet.
