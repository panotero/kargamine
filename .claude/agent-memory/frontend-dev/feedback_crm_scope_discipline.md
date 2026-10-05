---
name: feedback-crm-scope-discipline
description: crm.blade.php / logic_crm.js edits must stay strictly within the numbered brief items — this file has had two prior unauthorized-change reverts
metadata:
  type: feedback
---

When editing `resources/views/pages/crm.blade.php` or `resources/js/logic_crm.js`, treat the
"New Proposal" modal / proposal-row builder (`addProposalRow`, `applyRowDefaults`,
`recomputeFinalRate`, any `discount_type`/`base_rate`/`final_rate` field) as off-limits unless a
task explicitly names them.

**Update (2026-09-17):** That proposal-row builder no longer lives in `crm.blade.php`. As of the
Prospect-modal rebuild, `crm.blade.php`'s `LeadInfoModal`, `LeadAddProposalModal`, and
`LeadAddContainerModal` (plus every JS function that existed only to power them - `loadLeadInfo`,
`renderTimeline`, `renderContainers`, `renderAddresses`, `renderProposalCard`,
`addProposalRow`/`applyRowDefaults`/`recomputeFinalRate`, the change-stage and edit-contact
dropdown wiring, etc.) were confirmed dead - nothing linked to them anymore since both "New
Prospect" and every table row now open `<x-prospect-modal>` (`components/prospect-modal.blade.php`
+ `logic_prospect_modal.js`, see [[project_prospect_modal_build]]) - and were removed in a
dedicated cleanup pass. `crm.blade.php` is now just the table/pipeline-bar page shell (~114 lines)
plus `<x-prospect-modal />`; `logic_crm.js` is just the live table/counts/filters logic (~430
lines).

There is **no current live UI** for creating/appending proposal rate lines from the CRM side as of
2026-09-17 - the Prospect modal's own Tab 4 ("Request for Proposal") is still a bare placeholder
(see [[project_prospect_modal_build]]). A component matching the old field set exists at
`resources/views/components/new-proposal-modal.blade.php` (`<x-side-modal id="generateProposal">`,
backed by `app/View/Components/newProposalModal.php`) and also calls `window.reloadCrmData()` on
save, which looked at first glance like the successor - **it is not**: grep confirmed nothing in
the codebase includes `<x-new-proposal-modal />` or calls `initSideModal({modalId:
'generateProposal'})`, so it is itself orphaned/unreferenced. Don't assume it's live without
re-checking those two things yourself first; if a future task wires it up (or replaces it with
something else) as the Tab 4 implementation, update this note to point at whatever that turns out
to be, and treat it as the off-limits discount/rate-logic surface at that point.

**Why:** Two prior passes on this page were reverted — one added an unrequested discount/rate
feature and rewrote the proposal-row builder, another silently deleted a validation rule. The
tech lead now explicitly calls out "STOP AND READ THIS FIRST — scope discipline" at the top of
CRM-related briefs. The 2026-09-17 removal was itself explicitly named/authorized by that task's
brief (it named `LeadAddProposalModal`/`LeadAddContainerModal` directly as dead code to delete),
which is the narrow exception this memory always allowed for.

**How to apply:** Before finishing any crm.blade.php/logic_crm.js task, first re-verify with a grep
whether a live proposal-row builder exists yet anywhere (`recomputeFinalRate`/`discount_type`/
`base_rate`/`final_rate`, and check what actually opens/includes that file) - don't assume it's
still `crm.blade.php`, and don't assume `new-proposal-modal.blade.php` is live either. Only touch
pricing/discount logic if a brief explicitly names it. Note in the final report exactly what was
left untouched, with grep evidence.
