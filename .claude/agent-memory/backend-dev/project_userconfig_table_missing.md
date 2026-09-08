---
name: project-userconfig-table-missing
description: UserConfig model/controller target userconfig_table but no migration for that table exists anywhere in the repo — /api/userconfigs 500s on any environment that hasn't manually created it.
metadata:
  type: project
---

`app/Models/UserConfig.php` sets `protected $table = 'userconfig_table'`, and `UserConfigController` queries it directly. As of 2026-09-06 there is no migration file anywhere under `database/migrations/` that creates `userconfig_table` (confirmed via grep across migrations and seeders, and via `Schema::hasTable('userconfig_table')` returning false on the project's live dev MySQL DB). Hitting `GET /api/userconfigs` throws a raw 500 `QueryException` ("Base table or view not found").

**Why:** Discovered while wiring up routes for `UserConfigController` (Master Data settings page backend, 2026-09-06) — the brief described this controller as "already exists and is fully correct," which is true of the PHP code but not of the underlying schema. Out of scope for that task (brief only asked for routing, explicitly said don't touch the controller, and didn't mention a migration), so it was reported rather than fixed.

**How to apply:** Before relying on `/api/userconfigs` (or `UserConfig` in code) working in any environment, confirm `userconfig_table` actually exists there. If asked to fix this, the correct move is a new additive migration creating `userconfig_table` with at least `designation` (string) and `approval_type` (string/enum: pre-approval, final-approval, routing) columns plus timestamps — check with the user before assuming exact column types/lengths since the brief never specified them.
