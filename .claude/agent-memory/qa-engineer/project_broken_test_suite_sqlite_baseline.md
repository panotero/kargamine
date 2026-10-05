---
name: project-broken-test-suite-sqlite-baseline
description: php artisan test's known-good pre-existing baseline is 17 failed / 42 passed, all in Auth/Booking/VesselVoyage/ClientMasterForm — confirmed still current as of 2026-09-25.
metadata:
  type: project
---

`php artisan test` on this repo currently reports **17 failed, 42 passed** with zero relation to CRM/Prospect/ProposalRequest code. The 17 failures are always in: `AuthFlowTest` (2), `BookingDispatchTest` (1), `BookingEirTest` (1), `BookingGateScanTest` (2), `BookingTest` (4), `BookingVoyageTest` (5), `VesselVoyageLoadlistTest` (1), `ClientMasterFormTest` (1, "finance stage3 persists new fields and rejects an invalid cro"). Symptoms: Booking/Voyage tests get 403 instead of 201/2xx (looks like a permission/role-seeding gap under the SQLite test DB), ClientMasterFormTest gets 422 instead of 200 on CRO validation. Re-confirmed 2026-09-25 while reviewing a 5-item Prospect/ProposalRequest CRM change — same exact 17 test names, same counts, run before and after reading the diff.

Backend-dev's memory (`backend-dev/project_broken_test_suite_sqlite.md`) documents the same baseline and the 2026-09-17 fix that got the suite running at all (driver-guarded the MySQL-only `MODIFY...ENUM` migration).

**How to apply:** When `php artisan test` comes back with exactly this failure list/count, treat it as the pre-existing baseline, not a regression from the change under review — no need to re-litigate it in every report, just confirm the failing test names match this list and move on. If the count or the specific failing tests differ from this list, that's a real signal something changed — dig in rather than assuming it's "the usual flakiness."
