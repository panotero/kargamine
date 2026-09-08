---
name: project-client-masters-redesign
description: Direction of the Clients Master Data page (clientMasters.blade.php) list+modal redesign review
metadata:
  type: project
---

`resources/views/pages/clientMasters.blade.php` (list page titled "Clients Master Data" + its
modals) reviewed 2026-09-04 as the next screen in the session's redesign series (follows
[[project_crm_leads_list_redesign]] and the Proposals-page redesign). Review-only; user
synthesizes a mockup before any code. The separate create/resume wizard
`clientMasterForm.blade.php` is out of scope but is ALSO still old-style, so redesigning the
list+modal alone WILL open a seam (modern view/edit-complete modal vs. dated create wizard).

**Why the redesign:** same pre-redesign shell CRM and Proposals both had — 3 status cards with a
decorative full-width color bar (`w-full py-1 rounded-full bg-blue-500`, the exact
"decorative-bar-that-isn't-a-distribution" anti-pattern in VISUALS). Complete/Incomplete is a
2-state binary, NOT a 5-step workflow, so it should get a simple color-coded chip filter strip
(like Proposals' rounded-full chips) with NO arrows/stepper, plus the total moved into a header
stat — recommend the header stat be the **Incomplete** count since that's the actionable one.

Key findings (full report given 2026-09-04, not yet acted on):
- **createContractModal has drifted from the just-redesigned Proposals version** — clientMasters
  still uses the old `customConfirm('Apply this rate change?')` (proposals.blade.php dropped it),
  lacks the `describeRateChange()` diff-tooltip on the "(edited)" tag, lacks the row-hover
  affordance (`border-l-2 hover:border-orange-400`), and omits `· ccClientName` in the header.
  Conversely clientMasters has nicer padded icon buttons. Should converge to identical.
- **ClientDetailModal's one global Edit toggle across 5 unrelated sections** (company/addresses/
  contacts/finance/commodity) is a genuine usability problem: editing one contact email opens a
  giant form and `cdSaveInfoBtn` sequentially POSTs stage1→2→3, so a stage2 failure leaves
  stage1 already persisted (partial-save inconsistency). Recommend rail+tabs (matches Lead Info
  modal) with per-section scoped edit/save.
- **Bugs flagged (same class as the Proposals review's missing-`button:` finding):** main table
  `renderRemoteTable` call has no `emptyMessage` → zero-clients and zero-filtered look identical;
  proposal approve/disapprove/reject (`proposalDecisionAction`) and contract approve/terminate
  pass no `button:` to apiCall → no loading state, double-submittable; contract-terminate uses
  raw `window.prompt()` for the reason, bypassing the app's modal/showMessage conventions.
- Stage "X / 4" column is meaningless without naming the stage; incomplete rows silently route to
  the wizard while complete rows open the modal (identical-looking rows, different click
  behavior) — recommend an amber row accent + "Resume" affordance for incomplete.
- Green/emerald split for "good/active" within the same modal (proposal badge `green-100` vs
  contract "Active" `emerald-50`); pervasive dark: gaps in read-views/status badges/borders.

**How to apply:** re-read the file fresh before mockup/implementation — it may be under active
edit. Confirm the chip-strip (not stepper) direction and rail+tabs modal direction were accepted
before assuming they're the plan; both are recommendations, not signed-off.
