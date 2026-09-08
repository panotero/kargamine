---
name: project-crm-lead-form-redesign
description: Status and direction of the CRM "New Lead" full-page form (crmLeadForm.blade.php) redesign initiative
metadata:
  type: project
---

The CRM "New Lead"/"Edit Lead" full-page form (`resources/views/pages/crmLeadForm.blade.php`,
reached via "+ New Lead" or clicking an early-stage LEAD/QUALIFIED row in `crm.blade.php`,
loaded via `loadPage()` to `/page_crmLeadForm` — a real page navigation, not a modal) was
incrementally patched (accordion sections, dark mode, a sectioned Stage 2 booking-requirements
card) but never had a full redesign pass. User is planning a mockup and asked for an
independent structural review first (2026-09-03), same pattern as the separate
[[project_crm_lead_info_modal_redesign]] initiative for the read-only Lead Info modal —
these are two different UI surfaces, don't conflate them.

**Why:** Stage 1 ("Contact & Company Information") crams ~30 fields across 4 logical groups
(Lead Classification, Contact Information, Company Information + nested Authorized Signatory,
Addresses) into one accordion where only a handful of fields are actually `required`, but two
of those required fields (`company_name`, and "at least one address" enforced in JS) sit inside
sections that are **collapsed by default** — a real bug-adjacent UX gap, not just polish.
Stage 2 (repeatable container/cargo-requirement cards) is already sectioned (Container &
Quantity / Route / Cargo Details) and collapsible per-card but independently collapsible
(no exclusive-expand), so 3+ cards can all be open at once.

My recommendation (given in full 2026-09-03, not yet acted on): keep the 2-stage shape
(matches backend `saveStage1`/`saveStage2` split) but turn Stage 1's accordion into a 3-step
internal sub-wizard (1a: Classification+Contact merged, 1b: Company/Account, 1c: Addresses —
promoted out of collapse since it's a hard requirement). For the Corporate-only "Authorized
Signatory" block (currently a full duplicate of the Contact Information field shape), replace
with a "same as contact person above" checkbox that mirrors values by default and only reveals
the override fields when unchecked. For Stage 2, once 2+ requirement cards exist, switch to a
compact list/table view (reusing the existing `updateContainerCardSummary()` line format) with
exclusive expand (opening one row collapses others) rather than independently collapsible full
cards. Also flagged a real bug: `saveStage2Btn`'s handler unconditionally calls
`loadPage({link:'/page_crm'})` after save regardless of outcome — including the
"saved but missing requirements" branch, which tells the rep what's missing via toast and then
immediately navigates them away from the form where they'd fix it (crmLeadForm.blade.php
~line 1115). Should not navigate away when `response.data.is_complete` is false.

**How to apply:** if a future session picks up the mockup or implementation, check whether the
3-step-sub-wizard direction (vs. accordion) and the "same as contact" checkbox were accepted
or changed before assuming they're still the plan — this was a recommendation, not a signed-off
decision. Re-read `resources/views/pages/crmLeadForm.blade.php` fresh since it may still be
under active edit.
