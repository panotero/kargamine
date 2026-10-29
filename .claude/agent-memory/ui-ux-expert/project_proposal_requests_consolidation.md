---
name: project-proposal-requests-consolidation
description: 2026-10-20 review of the consolidated Proposal Requests page (merges assignment queue + Approvals + Signed/Contracts into one list/modal) - key verified bugs and facts for re-review
metadata:
  type: project
---

`resources/views/pages/proposal_requests.blade.php` merges three former pages (assignment
queue, `proposals.blade.php` Approvals, `proposal_signed_contracts.blade.php` Signed/Contracts)
into one list driven by `GET /api/proposalRequests` with a server-computed `effective_status`.
Row click branches on `pr.latest_client_proposal`: present -> opens the full `#ClientProposalModal`
(shared `logic_client_proposals_shared.js`, same id/body copied a 3rd time); absent -> opens the
new read-only `<x-proposal-request-info-modal>` (`logic_proposal_requests.js`).

**Verified findings from the 2026-10-20 review (given to tech-lead, not yet confirmed fixed):**

- **Real color-mapping collision, newly introduced by this merge:** `STATUS_PILL.assigned` and
  `STATUS_PILL.accepted` in `logic_proposal_requests.js` (lines ~20/24) use byte-for-byte identical
  blue classes, and the two filter chips (`proposal_requests.blade.php` lines 54-58 "Assigned" vs
  78-82 "Accepted") also use identical blue. These are two very different lifecycle stages
  (pre-proposal assignment vs. post-decision accepted/signed) sharing one hue in the same strip -
  recommend giving "Assigned" its own hue (e.g. indigo, unused elsewhere in this vocab) rather than
  reusing accepted's blue.
- **Dark-mode status-badge inconsistency surfaced by the merge, not new code:** `cpmStatusBadge`
  (shown in `#ClientProposalModal`'s header) renders via `logic_client_proposals_shared.js`'s own
  `statusPill()`/`STATUS_BADGE` map (lines 32-40) which has **zero `dark:` variants** on any entry
  (e.g. `bg-green-100 text-green-700` with no dark pair). This is unchanged copy-paste from
  `proposals.blade.php`/`proposal_signed_contracts.blade.php`, but it now sits on the *same page* as
  `logic_proposal_requests.js`'s own `STATUS_PILL` (used for the table + info-modal rail), which
  *does* have full `dark:` pairs for every entry. The two status-pill renderers for conceptually the
  same "status" now visibly disagree on dark-mode treatment within one page - worth unifying or at
  minimum giving `STATUS_BADGE` the missing dark: classes.
- **`#ClientProposalModal` header dark-mode gaps, pre-existing but now directly comparable:**
  `proposal_requests.blade.php` ~176-184 (`cpmCode` text has no `dark:text-white`, header `border-b`
  has no dark border color, close button is bare `✕` with no hover/dark classes) - copied verbatim
  from `proposals.blade.php`. Because this page also hosts the much more dark-mode-complete
  `<x-proposal-request-info-modal>` (explicit `dark:text-white`, `dark:border-zinc-800`, styled
  close button with hover state), the contrast between the two modals' polish is now visible
  back-to-back on one screen instead of on two separate pages.
- **`dark:text-zinc-900` without a paired `dark:bg-*`** on `#cpmSignedFile`, `#ccValidFrom`,
  `#ccValidTo`, `#ccSignedDate` (lines ~280, 341/345/349) - copied verbatim from
  `proposal_signed_contracts.blade.php`, same bug, now in a 3rd place.
- **Pre-existing, not a new bug:** `ccSaveBtn` "Create Contract" submit uses `bg-blue-600` instead of
  the design system's orange-500/600 primary - copied verbatim from the source page.
- **Confirmed safe / not a bug:** `priAssignBtnWrap` (can_assign) and `priOpenRequestBtnWrap`
  (can_open_my_request) in `proposal-request-info-modal.blade.php` are mutually exclusive at
  runtime - verified in `ProposalRequestAssignmentController::index()` (`can_assign` requires
  `effective_status === 'for_assignment'`; `can_open_my_request` requires the prospect to already
  have this user as `assigned_to`/`relationship_manager_id`, which in practice can't co-occur with
  `for_assignment`). So the "Assign" vs "Open Request" buttons never actually double up, despite
  each having its own `border-t` divider in the markup.
- **Confirmed safe / not overcrowded:** the merged `#ClientProposalModal` footer's two button
  groups (decision actions left, download/contract actions right) never render simultaneously at
  max density - decision buttons (Cancel/Disapprove/Approve/Reject) are status-gated to `[1,7]` (plus
  Reject also at `2`), while download/contract buttons are gated to `[2,4]` - so the only overlap
  case is Reject+Download at status 2, never the full 4-button left group next to the 2-button right
  group. Footer button gating logic lives in `logic_client_proposals_shared.js` lines 348-359.
- **Good pattern worth reusing:** `emptyMessage` in `renderRemoteTable` (`logic_proposal_requests.js`
  `renderTable()`) correctly distinguishes true-empty ("No proposal requests yet.") from
  filtered-empty ("No {status label} requests.") - matches the project's established convention.
- **Pre-existing accessibility gap, not new:** filter chips (`.prEffStatusBtn`/`.proposalStatusBtn`)
  are plain clickable `<div>`s with no `tabindex`/keyboard handler/`aria-pressed`, and the active-ring
  toggle communicates selection by color only. This exact gap already existed on `proposals.blade.php`
  before this merge (just 7 chips instead of 11) - same bug class as `.rpm-od-chip`/
  `.pm-client-type-btn` noted in `[[project-prospect-proposal-request-module]]`. By contrast the
  `.pri-tab-btn` tabs in the new info modal ARE real `<button>` elements - good, keep that pattern.

**How to apply:** if re-reviewing this page, re-check whether the `assigned`/`accepted` hue
collision and the `STATUS_BADGE` dark-mode gap in `logic_client_proposals_shared.js` have been
addressed - those are the two genuinely new (not-merely-inherited) issues from this consolidation.
