---
name: project-broken-test-suite-sqlite-baseline
description: Old baseline (17 failed/42 passed) is STALE as of 2026-10-20 — superseded by [[project-signatories-migration-breaks-full-suite]] (58 failed/1 passed). Kept for history only.
metadata:
  type: project
---

**STALE as of 2026-10-20 — see [[project-signatories-migration-breaks-full-suite]] for the current, correct state.** `php artisan test` no longer returns 17 failed/42 passed; an unguarded `dropForeign()` on SQLite in a different, later migration now breaks `migrate:fresh` entirely (58 failed/1 passed). Don't use the count below as a reference point until that's fixed and re-confirmed.

**Original 2026-09-25 baseline (history only):** 17 failed/42 passed, in `AuthFlowTest` (2), `BookingDispatchTest` (1), `BookingEirTest` (1), `BookingGateScanTest` (2), `BookingTest` (4), `BookingVoyageTest` (5), `VesselVoyageLoadlistTest` (1), `ClientMasterFormTest` (1). Backend-dev's memory (`backend-dev/project_broken_test_suite_sqlite.md`) documents this same baseline and an earlier 2026-09-17 fix for a different MySQL-only migration issue.

**How to apply:** Always check [[project-signatories-migration-breaks-full-suite]] first before reporting any `php artisan test` count as "the known baseline."
