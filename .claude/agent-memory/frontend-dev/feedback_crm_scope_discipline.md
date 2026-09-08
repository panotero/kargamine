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

Note: the `LeadInfoModal` was rebuilt 2026-09-02 into a rail (left) + tabbed pane (right,
Proposals/Requirements/Activity tabs) layout, replacing the older stacked-cards
Contact/Company/Deal relayout this memory used to describe. The rail (Contact card + Company/
Addresses `<details>`), stat strip (Deal Value/Assigned Rep/Primary Contact/Expected Close), tab
bar, and merged Activity timeline (`renderTimeline`, `#leadTimelineContainer`) are now the
"completed" structure — apply the same off-limits treatment to *this* structure unless a task
explicitly names it, rather than assuming the old 3-column grid described in earlier versions of
this memory still exists.

**Why:** Two prior passes on this page were reverted — one added an unrequested discount/rate
feature and rewrote the proposal-row builder, another silently deleted a validation rule. The
tech lead now explicitly calls out "STOP AND READ THIS FIRST — scope discipline" at the top of
CRM-related briefs.

**How to apply:** Before finishing any crm.blade.php/logic_crm.js task, grep for
`recomputeFinalRate`/`discount_type`/`base_rate`/`final_rate` and diff-check that those lines are
byte-identical to before (only shifted by line-number offsets from unrelated edits elsewhere in
the file). Only touch the narrow exception explicitly granted in a brief (e.g. wrapping a
*different* card's fields in a collapse/summary shell without touching calculation logic in the
same script block). If a fix seems to require touching pricing/discount logic or the completed
LeadInfoModal layout, stop and flag it in the report instead of proceeding. Note in the final
report exactly what was left untouched, with grep evidence.
