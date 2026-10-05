---
name: project-prospect-info-modal-sidebar
description: ProspectInfoModal (prospect-info-modal.blade.php) read-only detail modal — right-sidebar Contact Summary pattern, including the 2026-10-05 move of Requested Proposals + Activity out of a tab into that sidebar
metadata:
  type: project
---

`components/prospect-info-modal.blade.php` + `resources/js/logic_prospect_info_modal.js`
is the current read-only "Prospect Info" view (successor to the old removed
`LeadInfoModal` — see [[project_crm_lead_info_modal_redesign]]). Shape: a tab bar
(Locations/Contacts/Requirements/Origin&Destination) on the left/main area, plus a
persistent `md:w-[19rem]` right-column `#pimContactSummary` sidebar (identity fields
+ inline edit, a "Request for Proposal" CTA button, then Requested Proposals and
Activity & History).

**2026-10-05 change reviewed:** Requested Proposals (`#pimRequestedProposals`) and
Activity & History (`#pimActivityTimeline`) were moved out of a (now-removed)
top-level "Proposals & Activity" tab into that right sidebar, directly under the
Request-for-Proposal button, per explicit product-owner request (per the code
comment at prospect-info-modal.blade.php:304-307). "Locations" became the new
default main tab.

Findings from that review:
- **Real bug:** clicking "Request for Proposal" (`#pimRequestProposalBtn` handler,
  `logic_prospect_info_modal.js` ~905-926) mints a `ProposalRequest` and shows a
  success toast, but never calls `renderAll`/`reloadProspectInfo` afterward — the
  "Requested Proposals" card directly below the button doesn't show the new request
  until the modal is closed/reopened. This gap existed before too but was easy to
  miss inside a tab; now that the list sits right under the button it reads as
  obviously broken feedback.
- **Real a11y gap:** `#pimActivityTimeline` has `max-h-64 overflow-y-auto` nested
  inside the modal's own `overflow-y-auto` body, but the div has no `tabindex` and
  its rows (`activityRowHtml`) are plain text with no focusable elements — a
  keyboard-only user has no way to scroll past the first ~4-5 entries. This is a new
  pattern (VISUALS.md has no "nested-scroll timeline in a sidebar" entry) and should
  get a `tabindex="0"` + accessible-scroll treatment, then be written into
  VISUALS.md once fixed.
- **Real inconsistency:** `clientProposalRowHtml` (same file, ~739) renders
  `ClientProposal.status` as plain parenthetical gray text (`CP_STATUS_LABEL`), but
  `logic_client_proposals_shared.js` has a `STATUS_BADGE` colored-pill mapping for
  the exact same status values (1-7) used on the main Proposals pages. Same status
  concept, badge in one place, plain text in another.
- **Real polish bug:** `r.created_at` / `item.created_at` are interpolated raw
  (`esc(r.created_at)`) with no call to the existing global `window.formatDateTime`
  (`customFunctions.js:391`, already used by `logic_client_proposals_shared.js`) —
  these render as raw ISO8601 strings (e.g. `2026-10-01T08:23:45.000000Z`) instead
  of a human date.
- **Verified fine, not a drift:** the two new section labels use `font-semibold`
  rather than VISUALS' canonical `font-medium` field-label style — but this exactly
  matches an existing, consistent local sub-convention already present 4x in this
  same file (sibling "Contact Summary" label, and "Freights"/"Truckings"/"Charters"
  group headers in the Requirements tab all use `font-semibold`), while true
  field/column labels in the same file use `font-medium`. Reads as a deliberate
  group-header-vs-field-label distinction, not inconsistency — worth eventually
  adding to VISUALS.md explicitly so it isn't mistaken for drift later.
- Color-coding verified correct: `RFP_STATUS_PILL` hues match
  `logic_proposal_requests_mine.js`'s `STATUS_PILL` exactly for the 5 shared
  statuses; `ACTIVITY_DOT_COLOR` (green=success, red=danger/blocking, blue=default)
  matches VISUALS' semantic convention.
- Minor/inherited, not a new regression: `RFP_STATUS_PILL.cancelled`
  (`bg-zinc-100 text-zinc-400` / dark `bg-zinc-800 text-zinc-500`) has weak text
  contrast (~2:1, fails WCAG AA) — pre-existing in the pill map, just newly more
  visible in the sidebar's prominent position.

**How to apply:** if this modal/sidebar is touched again, re-check whether the
refresh-after-create bug and the timeline keyboard-scroll gap were fixed before
assuming they still apply. Mechanical checks worth repeating on any future pass
here: grep `apiCall(` for missing `button:`, and diff any `STATUS_BADGE`-style map
against sibling files for the same underlying status column.
