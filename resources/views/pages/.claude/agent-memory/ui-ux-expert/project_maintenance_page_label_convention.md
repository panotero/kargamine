---
name: project-maintenance-page-label-convention
description: maintenance.blade.php (Rate Maintenance / App Settings admin page) deliberately uses its own sentence-case label style, not VISUALS.md's uppercase CRM style
metadata:
  type: project
---

`resources/views/pages/maintenance.blade.php` ("Rate Maintenance" — Locations, Containers, Charge Types, Delivery Types, Lanes, General/Port Charges, Handling Fees, Trucking Tariffs, VAT Rates, Special Charges, Cargo Yard, General Lookups) was deliberately standardized on its own pre-existing sentence-case label convention: `text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1`, NOT VISUALS.md's uppercase small-caps CRM label style (`text-[11px] font-medium uppercase tracking-widest`).

**Why:** explicit scope decision confirmed with the user (2026-10) — this is a consistency cleanup of a utilitarian internal admin page, not a re-skin to match the customer-facing CRM redesign. VISUALS.md's uppercase label style is for the Lead/Prospect/booking redesign lineage; this page predates and is intentionally exempt from it.

**How to apply:** when reviewing this file (or doing a follow-up pass on it), do NOT flag the sentence-case labels as a VISUALS.md violation — that would be re-litigating a settled call. DO still check it against VISUALS.md's required-field wrapper accent (`border-l-2 border-orange-400 dark:border-orange-600 pl-3` + `req-asterisk` span) — that convention *was* adopted here and should stay applied to every required field, including ones added later. The 4 hand-rolled forms (Location/Container/Lane/General-Lookups) and the 1 shared `ENTITY_CONFIG`-driven generic form (`fieldWrapperHtml()` in the inline script) are the two structurally different code paths on this page — a fix in one often needs a parallel fix in the other since they don't share rendering code.

Known as-of-2026-10 gap found during review: the `addLOVModal`'s "LOV Code" field is NOT marked required in markup, but `ListOfValueController::store()` validates `addLOVCode` as `required` — a real backend/UI mismatch, not yet fixed as of this writing. Verify current state before relying on this if asked again later.
