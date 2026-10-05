---
name: project-broken-test-suite-sqlite
description: The MySQL-only ENUM MODIFY migration that red-screened the whole PHPUnit suite was fixed on 2026-09-17 (driver-guarded) — suite now runs, but ~17 pre-existing failures unrelated to CRM/Prospect remain.
metadata:
  type: project
---

`phpunit.xml` forces `DB_CONNECTION=sqlite` / `DB_DATABASE=:memory:` for tests. Until 2026-09-17, running any `RefreshDatabase` Feature test failed during migration with:

```
SQLSTATE[HY000]: General error: 1 near "MODIFY": syntax error (Connection: sqlite, SQL: ALTER TABLE client_proposal_rates MODIFY discount_type ENUM(...) NULL)
```

from `database/migrations/2026_08_22_164450_widen_discount_type_enum_for_client_proposal_and_contract_rates.php`'s raw `DB::statement(...MODIFY...ENUM...)` calls.

**Fixed 2026-09-17** (as part of a Prospect-modal backend brief): both `up()` and `down()` in that migration now wrap the `DB::statement` calls in `if (DB::getDriverName() === 'mysql') { ... }`. SQLite has no enum enforcement, so skipping is harmless there.

**Current state:** `php artisan test` now actually executes (no more immediate red-screen). As of 2026-09-17, running the full suite gives `17 failed, 42 passed`, all in `AuthFlowTest`, `BookingDispatchTest`, `BookingEirTest`, `BookingGateScanTest`, `BookingTest`, `BookingVoyageTest`, `VesselVoyageLoadlistTest`, and `ClientMasterFormTest` (one case: "finance stage3 persists new fields and rejects an invalid cro"). None of these touch Prospect/CRM/`proposal_requests` code (no test file references `Prospect` or `crm/prospects` at all) — confirmed unrelated to that brief's changes, but still a **standing pre-existing gap** in the suite that nobody has triaged yet. Symptoms seen: booking/voyage tests get 403 instead of 201 (looks like a permission/role-seeding gap under the SQLite test DB), and the ClientMasterFormTest case gets 422 instead of 200 on CRO validation.

**How to apply:** The old blanket "don't trust `php artisan test`, it red-screens everything" advice no longer applies — the suite is usable again for verification. But a green run is still not achievable without someone triaging the 17 failures above first. When verifying unrelated work, run the full suite and diff against this known-bad list rather than assuming any failure is yours; when touching Booking/Voyage/Auth/ClientMasterForm code, these failures may or may not already be there before your change — check by running just that test file on an untouched checkout first.
