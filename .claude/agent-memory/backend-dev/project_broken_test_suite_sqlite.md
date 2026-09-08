---
name: project-broken-test-suite-sqlite
description: The full PHPUnit suite (in-memory SQLite) fails on every Feature test due to a pre-existing MySQL-only migration statement — not caused by any one feature's changes.
metadata:
  type: project
---

`phpunit.xml` forces `DB_CONNECTION=sqlite` / `DB_DATABASE=:memory:` for tests. As of 2026-09-06, running any `RefreshDatabase` Feature test (e.g. `php artisan test tests/Feature/BookingTest.php`) fails during migration with:

```
SQLSTATE[HY000]: General error: 1 near "MODIFY": syntax error (Connection: sqlite, SQL: ALTER TABLE client_proposal_rates MODIFY discount_type ENUM(...) NULL)
```

from `database/migrations/2026_07_07_124800_add_rate_type_and_rate_value_to_proposals_rates_table.php` (or similarly-named migration) using a raw MySQL `MODIFY ... ENUM` statement that SQLite's grammar doesn't support.

**Why:** This is pre-existing and unrelated to whatever feature is being worked on — confirmed by running an untouched test (`BookingTest`) and seeing the identical failure with no relation to the files changed in that session.

**How to apply:** Don't assume a red `php artisan test` run means your change broke something — reproduce on an untouched test file first. Verification for anything under `RefreshDatabase` currently has to happen another way: hit the real dev MySQL DB directly (migrate against it, use `php artisan tinker`, or spin up `php artisan serve` + `curl` with a real session/CSRF flow) rather than relying on the PHPUnit suite until someone fixes this migration to use SQLite-compatible schema-builder calls instead of a raw MySQL statement. Report this as a standing risk whenever asked to verify via `php artisan test`.
