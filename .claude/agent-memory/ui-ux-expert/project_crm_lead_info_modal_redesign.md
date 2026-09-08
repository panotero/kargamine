---
name: project-crm-lead-info-modal-redesign
description: Status and direction of the CRM "Lead Info" modal (crm.blade.php, x-modal#LeadInfoModal) redesign initiative
metadata:
  type: project
---

The CRM "Lead Info" read-only detail modal (opened from `crm.blade.php` for leads in
OPPORTUNITY/NEGOTIATION/WIN/LOST status — see `OPEN_MODAL_STATUSES` in
`resources/js/logic_crm.js`) was incrementally patched over one evening (2026-09-02:
widened to `lg:max-w-[1100px]`, info card regrouped into Contact/Company/Deal, booking
requirement cards redesigned as route-strip cards) but has never had a top-to-bottom
IA/hierarchy pass. User is planning a full redesign mockup and asked for an
independent structural review first, before building the mockup for sign-off.

**Why:** current modal is 5 equal-weight stacked cards (Lead&Company Info, then
Addresses+Requirements side by side, then Proposals, then Activity+Notes side by
side) inside one scrolling body — but several of those cards *also* have their own
internal `max-h-72 overflow-auto` scrollbox, so it's nested-scroll-within-scroll.
Given this modal only opens for late-pipeline leads, static reference data (company
info, addresses) currently gets the same/more visual weight as the actually
decision-relevant sections (Proposals, Booking Requirements, deal status).

My recommendation (given in full 2026-09-02, not yet acted on): move to a persistent
left summary rail (identity/contact/company/deal, mostly reference data) + a
right-hand tabbed working area (Requirements+Proposals grouped/adjacent since
requirements feed proposal line prefill via `prefillFromLeadContainers`, plus a
merged Activity+Notes timeline instead of two twin cards). Also flagged: the
"add activity" popover doubles as the pipeline-stage-change control (amber warning
text, `activityStatusInput` select) — stage change is a high-stakes action buried in
a minor "+" popover and probably deserves its own header-level control instead of
living inside note-taking UI.

**How to apply:** if a future session picks up the mockup or implementation of this
redesign, check whether the 2-pane+tabs direction was accepted or changed by the
user before assuming it's still the plan — this was a recommendation, not yet a
signed-off decision. Re-read `resources/views/pages/crm.blade.php`'s
`LeadInfoModal` block and `resources/js/logic_crm.js` render functions fresh, since
the modal was still being actively edited around this date.
