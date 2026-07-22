# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

A Laravel 10 (PHP 8.1+) application for document tracking, CRM/lead management, client proposals & contracts, and finance tracking for a logistics/freight business (containers, lanes, ports, trucking tariffs, bookings). Server-rendered Blade views with jQuery/Alpine.js + Tailwind on the frontend, built via Vite.

## Commands

```bash
composer install
npm install
php artisan migrate
npm run build       # or `npm run dev` for a Vite dev server with HMR
php artisan db:seed
```

Default seeded login: `superadmin@email.com` / `Testing123`.

Tests (PHPUnit, uses in-memory SQLite — see `phpunit.xml`):
```bash
php artisan test                          # full suite
php artisan test --filter=AuthFlowTest    # single test class
php artisan test tests/Feature/AuthFlowTest.php
vendor/bin/pint                           # code style (Laravel Pint)
```

There is no JS test runner or linter configured — `npm run build`/`npm run dev` are the only frontend scripts.

## Architecture

### Routing is split across many files, and not all of them are wired up

`RouteServiceProvider` only auto-loads `routes/web.php` (web middleware) and `routes/api.php` (api middleware, `/api` prefix). Everything else is pulled in via explicit `require` at the bottom of those two files:

- `web.php` requires `page.php` (page-render routes, all under `auth`+`check.status`+`prevent-back-history` middleware) and `mailer.php`, then `auth.php` (Breeze auth routes) outside that group.
- `api.php` requires `api_maintenance.php` from inside its `auth` middleware group.
- `routes/api_booking.php`, `routes/api_contracts.php`, `routes/api_master.php`, and `routes/pageApi.php` are **not required anywhere** — treat them as dead/orphaned files, not live endpoints, unless you wire them in yourself.

Route files are organized by feature and grouped with `Route::prefix(...)`. When adding endpoints, find the matching prefix group in `api.php` (or the relevant sub-file) rather than creating a new top-level file.

### Page vs API split

Blade "page" routes (`/page_*`, defined in `routes/page.php`) just return a full-page `view(...)` shell from `PageController`. All data loading/mutation happens client-side afterward via calls into `/api/...` routes. When working on a feature, expect the Blade view to be mostly a container that JS then populates.

### Frontend: single JS bundle, no per-page bundling

`resources/js/app.js` is the one Vite entry point (see `vite.config.js`) and imports every shared script (`apihandler.js`, `customFunctions.js`, `datatableHandler.js`, `navmenu.js`, `logic_crm.js`, `remoteTable.js`, etc.) globally onto every page — there's no route-based code splitting. Page-specific logic lives in files like `logic_crm.js` and is guarded internally (e.g. checking for the presence of expected DOM elements) rather than only being loaded on relevant pages.

`resources/js/apihandler.js` defines the shared fetch helpers used everywhere — prefer `window.apiCall({mode, isJson, payload, url, button})` (handles CSRF header injection, button loading state, retries via `fetchWithRetry`, and error toasts via `showMessage`). Older helpers (`apiJSONPOST`, `apiJSONGET`, `apiPOST`, `apiRequest`) still exist and are used in some older pages, but new code should use `apiCall`.

### Domain model shape

Core CRM/sales flow: `CrmLead` → (on conversion) `ClientMaster` → `ClientProposal` → `ClientContract`. Notable points:

- `CrmLead` and `ClientMaster` use `HasUuids` with `uniqueIds() => ['uuid']` — they have both an internal auto-increment `id` (used for FK relations like `lead_id`, `client_id`) and a public-facing `uuid` (used in route parameters, e.g. `/api/crm/leads/{uuid}`). Don't confuse the two when building queries or routes.
- Multi-stage forms are modeled as "stages" — e.g. `CrmLead::saveStage1`/`saveStage2`, `ClientMaster::saveStage1`/`saveStage2`/`saveStage3` — with a `stageCompletionFlags()`/`recomputeCompletion()` pattern tracking which stage a record has reached. Follow this pattern for any new multi-step entity forms.
- `ClientProposal` approval/rejection authorization is config-driven, not hardcoded: see `config/client_proposal_workflow.php` (`approver_roles`, `reject_roles`) plus the special case that a client's assigned Sales Rep (`client_masters.sales_rep_id`) can always reject their own client's proposals. Check `ClientProposal::canBeApprovedBy`/`canBeRejectedBy` before changing approval logic.
- Role checks generally compare `user->role_name` against values in the `setting_role` table (see `AuthServiceProvider`'s `isSuperAdmin` gate and `app/Support/RoleHelper.php`), not a permissions package.

### Auth & middleware

Standard Laravel Breeze auth (`routes/auth.php`) plus custom middleware: `check.status` (`CheckUserStatus` — blocks deactivated users), `prevent-back-history`, and `EnsureSingleSession` (global `web` group middleware — logging in elsewhere invalidates the previous session). The `can:isSuperAdmin` gate (defined in `AuthServiceProvider`) checks `role_name === 'superadmin'`.

### PDF generation

Proposals and contracts render PDFs via `barryvdh/laravel-dompdf`; Blade templates for these live in `resources/views/pdf/`.

### App-wide color theme (Developer Option → Theme, `/page_theme`)

`AppThemeSetting` is a singleton DB row (`AppThemeSetting::current()`) holding `main_color`/`accent_color`/`button_secondary_color`/`button_danger_color` (each a Tailwind hue name, e.g. `orange`) plus `dark_mode` (`light`/`dark`/`system`). `tailwind.config.js` hijacks the `blue`, `orange`, and `red` color names (already the app's de facto main/accent/danger colors — see the many pre-existing `bg-blue-*`/`bg-orange-*`/`bg-red-*` usages) plus a new `theme-secondary` token, remapping their shades to `rgb(var(--tw-color-{main,accent,secondary,danger}-{shade}))`. `layouts/app.blade.php` renders the real RGB values into a `:root { }` `<style>` block from `App\Support\TailwindPalette` (the single source of truth for Tailwind's default hex palette — keep the JS copy in `pages/settings/theme.blade.php` in sync if it's ever edited) and bootstraps the dark class per `dark_mode` before first paint (`darkMode: 'class'` in `tailwind.config.js`, not the old `media` default). Because this page-shell `<head>` is only rendered once per session (see below), theme changes apply app-wide without touching individual pages — but new hardcoded `blue-*`/`orange-*`/`red-*` classes anywhere in the app now track the *picked* color, not a fixed hex, which is intentional.

Note `tailwind.config.js`'s `content` includes `resources/js/**/*.js` — several Tailwind classes (e.g. the active-pagination/table-row-hover colors in `remoteTable.js`) are only ever referenced from JS template strings, not Blade files, and would otherwise be invisible to the build's static scanner. `layouts/app.blade.php` also still loads the Tailwind Play CDN script (`cdn.tailwindcss.com`) as a JIT safety net for anything the static scanner still misses; it runs before `@vite(...)` in `<head>`, so the Vite-built stylesheet's rules come later in the DOM and win any cascade tie.

### The app is a client-rendered shell, not classic multi-page

`GET /app` (route name `dashboard`, `routes/web.php`) renders `resources/views/dashboard.blade.php` **once** per session — the persistent sidebar/topbar/`<x-app-layout>` shell. Its `#content` div is then populated by `window.loadPage()` (`resources/js/navmenu.js`), which fetches a `/page_*` route's HTML fragment via AJAX and swaps it in — the browser never actually navigates. This is why `<head>`-level concerns (the theme `<style>`/dark-mode bootstrap above, the Tailwind CDN script, Vite assets) only need to live in `layouts/app.blade.php` once, and why individual `pages/*.blade.php` views are bare fragments with no `<html>`/`<head>`/nav chrome of their own.
