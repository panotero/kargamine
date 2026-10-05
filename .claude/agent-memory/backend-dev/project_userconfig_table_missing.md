---
name: project-userconfig-table-missing
description: RESOLVED 2026-09-17 — userconfig_table now has a migration (2026_09_06_020000_create_userconfig_table) and exists on the dev DB. Historical note only.
metadata:
  type: project
---

**Superseded.** `app/Models/UserConfig.php` sets `protected $table = 'userconfig_table'`; as of 2026-09-06 there was no migration for it anywhere in the repo, so `/api/userconfigs` 500'd on any environment.

As of 2026-09-17, a migration `database/migrations/2026_09_06_020000_create_userconfig_table.php` exists and runs cleanly as part of `migrate:fresh` — confirmed via `Schema::hasTable('userconfig_table')` returning true on the project's live dev MySQL DB after a fresh migrate. Whoever/whenever this was added isn't tracked here — if `/api/userconfigs` is reported broken again, re-verify with `Schema::hasTable()` rather than assuming this memory still applies verbatim, but the missing-migration issue itself is resolved.
