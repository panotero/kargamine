---
name: project-prospect-proposal-request-module
description: Review notes on the new Prospect/ProposalRequest CRM module (replaces old CrmLead) - request-proposal-modal, add-origin-destination-modal, proposal-request-summary-modal, prospect-modal
metadata:
  type: project
---

As of 2026-09-25 the CRM intake/proposal-request flow has been fully rebuilt: `CrmLeadController`/`CRMLead`/`CrmLeadAddress`/`CrmLeadContainer`/`CrmNote`/`CrmActivity` etc. are deleted, replaced by `Prospect` + `ProposalRequest` (see CLAUDE.md's "Domain model shape"). The reviewed files
(`resources/views/components/{request-proposal-modal,add-origin-destination-modal,proposal-request-summary-modal,prospect-modal}.blade.php`
and `resources/js/logic_prospect_{request_proposal,add_modals,info_modal,modal}.js`) were all **untracked/new** at
review time - no git-diff baseline existed, this was a from-scratch design-system-consistency review, not a regression check.

**Why this matters for future reviews:** this module is still being built out incrementally (Products tab's 5
sub-product forms, 4 sub-item modals for Ancillary/TopLoad/CharterCargoInfo/CharterPorts). Expect more new
untracked files in this area in future sessions - check `git status` before assuming git diff will show anything.

**Findings from the 2026-09-25 review** (given to tech-lead, not yet confirmed fixed):
- `apiCall({..., button})`'s double-submit protection is applied inconsistently *within the same file*
  (`logic_prospect_request_proposal.js`): `saveCompanyDetails`/`submitProduct`/`submitProposalRequest` pass `button`,
  but `addOdLocation`/`addSignatory` (and the delete/remove actions) do not - real duplicate-submit gap on the "+ Add"
  buttons specifically for OD locations and signatories.
- `logic_prospect_add_modals.js` has **no `esc()` helper at all** (confirmed via grep - zero matches), unlike its
  sibling files (`logic_prospect_request_proposal.js`, `logic_prospect_info_modal.js`, `logic_prospect_modal.js`
  each define and use one) - every address field rendered into `AddOriginDestinationModal`'s chips/preview
  (`renderAodmChips`/`aodmPreviewFieldsHtml`) is unescaped raw interpolation. Worth re-checking if this file is
  touched again.
- The two "add an Origin/Destination address" entry points (wizard tab `rpmAddOdBtn` vs standalone modal
  `aodmSaveBtn`) render the *same* confirm action at different visual weight: wizard uses the secondary
  zinc-bordered "+Add"-style button (matching the wizard's own in-tab-list-add convention, e.g. `rpmAddSignatoryBtn`),
  standalone modal uses primary `bg-orange-500` (matching its own sibling `AddLocationModal`'s `almSaveBtn`). Neither
  is "wrong" against its own immediate siblings, but the task brief explicitly wanted the two hosts to look the same
  and they don't.
- New chip pickers (`.rpm-od-chip`/`.aodm-chip`) and the new Individual/Corporate segmented toggle
  (`.pm-client-type-btn`) communicate selection by color only (border-orange-500 + bg tint) with no `aria-pressed`
  anywhere - screen-reader users get no selection signal. Same gap pre-existed on `.pm-social-platform-btn` before
  this change, so it's a continuation of an existing gap, not a new regression, but worth fixing all three together.
- Two small dark-mode contrast gaps introduced: `switchRpmTab`'s active-tab classes (`text-orange-600`, no
  `dark:text-orange-400`) and `.pm-client-type-btn`'s active state (same gap) - both deviate from the
  `text-orange-600 dark:text-orange-400` pairing used everywhere else in the app for orange text-on-surface
  (e.g. `booking.blade.php:664`, `containerInventory.blade.php:554`, `clientMasterForm.blade.php:1428`).
- The clickable proposal-request card (`.pim-proposal-card`, `logic_prospect_info_modal.js` `renderProposalsList`)
  is a `<div>` with a click listener only - no `tabindex`/keyboard handler, not reachable via keyboard.
- What's done well and worth reusing as a reference: `buildProposalRequestRecapHtml()` is shared verbatim between
  the wizard's own Confirmation tab and `ProposalRequestSummaryModal` (exposed on `window`), so the two recaps can
  never visually drift - good DRY pattern worth pointing to when reviewing future "recap/summary" UI in this app.
  Also good: `STATUS_PILL` correctly keeps "Cancelled" on inert zinc/gray (not red) while the actual destructive
  "Cancel Request" action button is red - exactly the red-vs-gray semantic split VISUALS.md calls for.

**How to apply:** if asked to re-review this module, re-grep for `esc(` in whichever files changed and re-check
whether `button:` is passed to every mutating `apiCall` - these are the two mechanical checks that turned up the
most findings here and are fast to re-run.
