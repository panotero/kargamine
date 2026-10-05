# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

A Laravel 10 (PHP 8.1+) application for document tracking, CRM/lead management, client proposals & contracts, and finance tracking for a logistics/freight business (containers, lanes, ports, trucking tariffs, bookings). Server-rendered Blade views with jQuery/Alpine.js + Tailwind on the frontend, built via Vite.

**Redesigning or building a new UI screen? Read `VISUALS.md` first.** It documents the visual/interaction patterns established across already-redesigned screens (the Lead Info modal, the New Lead form, booking-requirement cards) — color-coding conventions, empty-state treatments, the rail+tabs and wizard patterns, etc. — so new work reads as the same app, not a bolted-on redesign.

**Adding or changing a module, page, or a meaningful mutating action? Read and update `MODULES.md`.** It catalogs every module/submodule in the app (route, controller, actions) and which ones are covered by the `permissions` table / `permission:` middleware, `nav.access:` middleware, or neither. When you add a new module or submodule, add it to `MODULES.md` first (or in the same change). When `MODULES.md` changes, treat that as a prompt to review whether the `permissions` table needs a new row for the new action(s) — see that file's "Keeping this in sync with Roles & Permissions" section for the exact steps (add a `permissions` row, gate the route with `->middleware('permission:the.key')`, grant it to the relevant roles, done — the Roles & Permissions page at `/page_roles_permissions` reads permissions live, no UI code changes needed).

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

**Do not run `npm run build`.** The user normally has `npm run dev` (Vite dev server with HMR) running already, and builds manually whenever they actually need a production build. Editing files under `resources/js/` or `resources/css/` is fine — the dev server picks them up — but don't invoke `npm run build` yourself.

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

### Searchable selects (`resources/js/searchableSelect.js`)

`window.makeSearchableSelect(selectEl)` turns a native `<select>` into a type-to-search combobox (text input + filtered dropdown that also opens on focus/click). Built in-house on purpose — the project has no Select2/Choices/TomSelect and deliberately avoids adding one. Use this for any new long-option select instead of hand-rolling another copy; it's currently wired onto the origin/destination location+port selects in `crm.blade.php` (two independent blocks), the Prospect modal (`components/prospect-modal.blade.php` / `logic_prospect_modal.js`), and `clientMasters.blade.php`.

- The native `<select>` is only hidden (`style.display = 'none'`) — it **stays in the DOM and remains the source of truth**. Existing code that reads `.value` off it, and existing `change` listeners on it (e.g. the cascading location→port filters), keep working untouched; picking an option sets `selectEl.value` and dispatches a real bubbling `change`.
- Returns a handle `{refresh, destroy, input, wrapper, select}`, also stashed on `selectEl._searchableSelect`. Idempotent — calling it twice returns the same handle.
- **`handle.refresh()` must be called after any code that rebuilds the options via `.innerHTML = ...`, or that assigns `.value`/`.disabled` programmatically** (the cascading location→port pattern does exactly this). Without it the widget shows a stale option list. `refresh()` never dispatches `change`. The pages above define a local `refreshSearchable(el)` one-liner for this.
- The text input inherits the select's Tailwind classes minus any class ending in `-select` (those are JS hooks, not styling), so per-page styling is preserved — don't name a styling class `*-select`.

### Domain model shape

Core CRM/sales flow: `Prospect` → (on conversion) `ClientMaster` → `ClientProposal` → `ClientContract`. Notable points:

- The CRM intake entity is `Prospect` (table `prospects`, formerly `CrmLead`/`crm_leads`). It is created/edited entirely through a **4-tab modal** (`components/prospect-modal.blade.php` + `resources/js/logic_prospect_modal.js`) — Identity → Contact Information → Requirements → Request for Proposal — opened from `pages/crm.blade.php` for both "New Prospect" and row-click edit. There is no separate page form anymore (the old `/page_crmLeadForm` was retired). Contact persons live in `prospect_contacts` (+ `prospect_contact_channels`, `prospect_contact_addresses`); the Requirements tab creates a `proposal_requests` row (unique per prospect, carries an `RQ-YY-XXXXXX` code from `ProposalRequest::generateNextCode()`) whose container rows are `proposal_request_containers`. `prospects.relationship_manager_id` (FK → users) is set on the Identity tab; the old `crm_lead_containers` table is renamed `prospect_containers_legacy` and frozen (no new writes).
- `Prospect` and `ClientMaster` use `HasUuids` with `uniqueIds() => ['uuid']` — they have both an internal auto-increment `id` (used for FK relations like `prospect_id`, `client_id`) and a public-facing `uuid` (used in route parameters, e.g. `/api/crm/prospects/{uuid}`). Don't confuse the two when building queries or routes.
- Multi-stage forms are modeled as "stages" — e.g. `Prospect::saveStage1`/`saveStage2`, `ClientMaster::saveStage1`/`saveStage2`/`saveStage3` — with a `stageCompletionFlags()`/`recomputeCompletion()` pattern tracking which stage a record has reached. `Prospect::stageCompletionFlags()` now returns three gates (identity / contacts / requirements) that gate the modal's 4th tab and drive OPPORTUNITY promotion. Follow this pattern for any new multi-step entity forms.
- **`container_class_id` and `container_size_id` are nullable end-to-end** — through the catalog (`container_variants`) and now the rate-storage layer too (`client_proposal_rates`, `client_contract_rates`, `booking_lines`). A null class means a "base price, no class" line (the container has no configured classes); a null size means "no fixed size" (Loose Cargo / Rolling Cargo, priced by class instead). `Container::syncCatalog()` generates these class-only / size-only / base variant combos. Any new code touching these two columns must treat them as optional — never validate them as `required`, and use `whereNull(...)` rather than `where(..., $id)` when matching a variant with a null side. `container_variant_id` is the field that actually identifies a priced line and *is* required. In the Create Proposal UI the class `<select>` uses a synthetic `"__base__"` sentinel for the no-class option; that sentinel is internal to the cascading logic and is translated to `null` where the API payload is built.
- `ClientProposal` approval follows the team hierarchy (see `App\Services\TeamService` and the "Team hierarchy access" note below), not a role list: only the team leader over the prospect's assigned rep (`prospects.assigned_to`) - their direct leader, or a leader further up the team tree - can approve/disapprove, plus superadmin always can. Rejection is still config-driven (`config/client_proposal_workflow.php`'s `reject_roles`) plus the special case that a client's assigned Sales Rep (`client_masters.sales_rep_id`) can always reject their own client's proposals. Uploading the signed document (`ClientProposal::canBeSignedBy`) is open to any member of that same team (leader included), not just the leader. Check `ClientProposal::canBeApprovedBy`/`canBeRejectedBy`/`canBeSignedBy` before changing this logic.

### Team hierarchy access (`App\Services\TeamService`)

`Team` rows form a tree via `parent_id`/`children()`; each `User` belongs to at most one team (`users.team_id`) and may be `is_team_leader` within it. `TeamService::accessibleTeamIds($user)` returns just the user's own team if they're a regular member, or their team plus every descendant team if they're a leader (walked in-memory via `descendantTeamIds()`, not a DB recursive CTE) - i.e. a team leader's reach cascades down through sub-teams and their own leaders ("the pyramid"). `TeamService::accessibleUserIds($user)` resolves that to actual user ids. superadmin bypasses this entirely wherever it's checked (via `RoleHelper::hasAnyRole($user, ['superadmin'])`), rather than being folded into the tree. This is currently wired into: the CRM prospect list (`ProspectController::index` - a member only sees prospects assigned to them, a leader sees their whole subtree's prospects) and `ClientProposal` approval/signing (above). Apply the same `TeamService` calls if extending team-based visibility to other modules - don't hand-roll a second tree walk.
- Role checks generally compare `user->role_name` against values in the `setting_role` table (see `AuthServiceProvider`'s `isSuperAdmin` gate and `app/Support/RoleHelper.php`), not a permissions package.

### Auth & middleware

Standard Laravel Breeze auth (`routes/auth.php`) plus custom middleware: `check.status` (`CheckUserStatus` — blocks deactivated users), `prevent-back-history`, and `EnsureSingleSession` (global `web` group middleware — logging in elsewhere invalidates the previous session). The `can:isSuperAdmin` gate (defined in `AuthServiceProvider`) checks `role_name === 'superadmin'`.

### PDF generation

Proposals and contracts render PDFs via `barryvdh/laravel-dompdf`; Blade templates for these live in `resources/views/pdf/`.

### App-wide color theme (Developer Option → Theme, `/page_theme`)

`AppThemeSetting` is a singleton DB row (`AppThemeSetting::current()`) holding `main_color`/`accent_color`/`button_secondary_color`/`button_danger_color` (each a Tailwind hue name, e.g. `orange`) plus a `dark_mode` field (`light`/`dark`/`system`) that is currently **inert** — see below. `tailwind.config.js` hijacks the `blue`, `orange`, and `red` color names (already the app's de facto main/accent/danger colors — see the many pre-existing `bg-blue-*`/`bg-orange-*`/`bg-red-*` usages) plus a new `theme-secondary` token, remapping their shades to `rgb(var(--tw-color-{main,accent,secondary,danger}-{shade}))`. `layouts/app.blade.php` renders the real RGB values into a `:root { }` `<style>` block from `App\Support\TailwindPalette` (the single source of truth for Tailwind's default hex palette — keep the JS copy in `pages/settings/theme.blade.php` in sync if it's ever edited). Because this page-shell `<head>` is only rendered once per session (see below), color-theme changes apply app-wide without touching individual pages — but new hardcoded `blue-*`/`orange-*`/`red-*` classes anywhere in the app now track the *picked* color, not a fixed hex, which is intentional.

**Dark mode currently just follows the browser/OS preference, not the DB setting.** `tailwind.config.js` has no `darkMode` key set (so it's on Tailwind's default `media` strategy), meaning every `dark:` utility across the app reacts only to `prefers-color-scheme`. There used to be a `darkMode: 'class'` config plus a bootstrap `<script>` in `layouts/app.blade.php` that added/removed a `.dark` class on `<html>` based on `AppThemeSetting::current()->dark_mode`, letting the Theme page force light/dark regardless of OS — that script has been removed because it was silently not working, and the `class` strategy along with it. The Theme page's "Dark Mode" picker (`pages/settings/theme.blade.php`) and the `dark_mode` column are still in place as a placeholder for a future reimplementation, but currently do nothing. Don't assume picking "Light" or "Dark" there has any effect until this is rebuilt.

### The app is a client-rendered shell, not classic multi-page

`GET /app` (route name `dashboard`, `routes/web.php`) renders `resources/views/dashboard.blade.php` **once** per session — the persistent sidebar/topbar/`<x-app-layout>` shell. Its `#content` div is then populated by `window.loadPage()` (`resources/js/navmenu.js`), which fetches a `/page_*` route's HTML fragment via AJAX and swaps it in — the browser never actually navigates. This is why `<head>`-level concerns (the theme `<style>` block, Vite assets) only need to live in `layouts/app.blade.php` once, and why individual `pages/*.blade.php` views are bare fragments with no `<html>`/`<head>`/nav chrome of their own.
