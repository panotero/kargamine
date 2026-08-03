---
name: project-client-master-rebuild
description: Client Master feature was restructured (contacts/finance/ancillary/mnemonic/account manager) on 2026-08-03 — key shapes and gotchas for follow-up work.
metadata:
  type: project
---

On 2026-08-03, the Client Master backend was substantially reworked (feature branch
`feature/booking-capture-automation`, but this was an independent brief, not part of
booking capture). Key structural facts likely to matter for follow-up work:

- `client_masters.client_mnemonic` (nullable, unique) and `client_masters.account_manager_id`
  (FK to users, nullable) were added. `account_manager_id` is a **one-time snapshot** taken
  at `ClientMaster` creation (stage 1) of the creating CSR's team leader
  (`User::where('team_id', $csr->team_id)->where('is_team_leader', true)->first()`), not a
  live-updating relation — a later change of team/leader does not move existing clients.
- `client_finance` was restructured: old payment/billing/courier columns
  (payment_mode, invoice_submission, payment_method, bank_name, document_handling, etc.)
  were dropped in favor of new TIN/tax/credit fields (tin_number, tax_status,
  withholding_tax_code, tax_percent, withholding_tax_percent, mode_of_payment,
  cro_user_id, max_declared_value). `client_billing` table/model/relation was dropped
  entirely — billing info now folds into client_finance.
- `client_contacts` was restructured from a flat contact_name/contact_number shape to
  first_name/last_name/title/gender/landline_number/mobile/email (+ _type suffix fields),
  and now has its own nested `client_contact_addresses` table (one-to-many per contact),
  mirroring the client_addresses shape.
- New `client_ancillary_services` table (stage 4 of the Client Master wizard, optional —
  stageCompletionFlags() always marks stage 4 `true`). Its client_code/client_mnemonic/
  client_business_name columns are **server-set snapshots** from the parent
  ClientMaster/ClientFinance at save time — any client-submitted values for those three
  fields must be discarded, not persisted.
- New `Credit Officer` role (setting_role id 5) + seeded user `creditofficer@email.com` /
  `Testing123`, and `GET /api/users/byRole?role=X` (case-insensitive, active-only) added to
  populate the CRO dropdown. Route registered before `/api/users/{id}` intentionally.
- Pre-existing pattern noise found while doing this (left alone, not fixed): `LovController`
  methods (addressType/leadSource/industry/organizationType/etc.) return a bare
  collection/array, not the `{success, data}` envelope — inconsistent with the rest of the
  app's convention but matches every other LOV endpoint already there. Also,
  `ClientMasterController::saveStage2/3/4` use `$request->validate()` (default Laravel
  `{message, errors}` 422 shape) rather than the manual `Validator::make` +
  `{success:false, invalid_fields}` shape used in `saveStage1` — this was already the case
  for stage2/3 before this rebuild; stage4 was added matching that existing (inconsistent)
  pattern rather than introducing a third convention.
