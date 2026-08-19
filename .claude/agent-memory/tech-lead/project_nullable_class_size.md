---
name: nullable-class-size-pipeline
description: container_class_id/container_size_id made nullable through the rate-storage layer, and the pre-existing bugs found while doing it
metadata:
  type: project
---

On 2026-08-19 `container_class_id`/`container_size_id` were made nullable on `client_proposal_rates`, `client_contract_rates`, and `booking_lines` (migration `2026_08_19_120000_...`), plus validation relaxed from `required` to `nullable` in `ClientProposalController::rateRules()` and `ClientContractController::store()`.

**Why:** The catalog table `container_variants` had already been made nullable on both columns (migrations 2026_08_12 / 2026_08_13) so containers with no configured classes, or no fixed size (Loose Cargo / Rolling Cargo), still get a valid variant. The rate-storage layer downstream never caught up, so a legitimate base variant hit a NOT NULL constraint. `booking_lines` was a genuine latent bug — `BookingController` populates those columns straight from an already-nullable resolved variant.

**How to apply:** Treat these two columns as optional everywhere. `container_variant_id` is the required field that identifies a priced line.

**Two pre-existing frontend bugs found and fixed during this work** (worth knowing, since the same shape may recur elsewhere):
- `crm.blade.php` sent the *variant* id as `container_size_id` — the size `<option>`'s `value` is intentionally the variant id (so class-only, no-size variants stay reachable), and the payload builder was reading `.value` off a select tagged `data-field="container_size_id"`. Fixed by carrying the real size in a separate `data-size-id` attribute.
- `clientMasters.blade.php` built its size options from `v.container_size.id` with no null guard — a TypeError on any no-fixed-size variant.

Lesson: in these rate-row builders, a select's `data-field` name does not reliably tell you what its `.value` holds. Check the option template before trusting it.

Related: [[searchable-select-decision]]
