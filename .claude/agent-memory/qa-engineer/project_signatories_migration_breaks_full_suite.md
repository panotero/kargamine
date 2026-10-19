---
name: project-signatories-migration-breaks-full-suite
description: database/migrations/2026_10_14_131007_add_snapshot_fields_to_proposal_request_signatories_table.php unconditionally calls dropForeign() on SQLite, breaking php artisan test entirely (100% failure, not just the known baseline)
metadata:
  type: project
---

`database/migrations/2026_10_14_131007_add_snapshot_fields_to_proposal_request_signatories_table.php`
calls `$table->dropForeign(['prospect_contact_id'])` unconditionally in both `up()` and `down()`,
with no SQLite guard (unlike the established pattern elsewhere in the codebase, e.g.
`2026_07_09_000939_drop_column_from_container_table.php`, which checks
`Schema::getConnection()->getDriverName() !== 'sqlite'` before calling `dropForeign()`).
SQLite never supports `dropForeign()` — Laravel throws `BadMethodCallException` the moment
any migration tries it, regardless of driver-name checks elsewhere.

Because the test suite migrates fresh on in-memory SQLite (`phpunit.xml`), this migration failing
means **every single test in the suite fails** (confirmed: 58 failed / 1 passed on 2026-10-20,
with `migrate:fresh` against a throwaway file-based SQLite DB pinpointing this exact migration
as the first failure in the chain, well before any charter_services migrations run).

**Why:** This predates and is unrelated to the charter_services/Products-tab work reviewed on
2026-10-20 — it's dated 2026-10-14, six days before the charter work's migrations. It is NOT
caused by that diff.

**How to apply:** Supersedes [[project_broken_test_suite_sqlite_baseline]] — that 17-failed/42-passed
baseline is now **stale**; as of this migration's introduction, `php artisan test` fails 100% until
this migration is fixed to guard `dropForeign()` on SQLite (or is otherwise reworked). When asked to
run the test suite, expect total failure for this reason and say so explicitly rather than reporting
pass/fail counts as if they reflect the change under review. Flag this to tech-lead for routing to
backend-dev — it blocks automated verification of *any* future backend change until fixed.
