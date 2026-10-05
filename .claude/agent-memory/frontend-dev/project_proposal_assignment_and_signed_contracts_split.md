---
name: project-proposal-assignment-and-signed-contracts-split
description: Management's CSR/RM assignment queue (new page) that gates the RFP wizard, plus splitting proposals.blade.php into separate Approvals and Signed/Contracts pages sharing one JS file.
metadata:
  type: project
---

2026-09-30 pass built three things together (backend endpoints were already wired in parallel by
backend-dev before this pass started - see `MODULES.md` section 3 for the authoritative module
breakdown):

1. **Management assignment queue** - NEW `resources/views/pages/proposal_requests.blade.php` +
   `resources/js/logic_proposal_requests.js` (registered in `resources/js/app.js`). Lists every
   `ProposalRequest` (`GET /api/proposalRequests`, paginator + `status_counts:{all,awaiting,assigned}`)
   behind an assignment-status pill strip (Awaiting Assignment/Assigned/All, default 'all', mirrors
   `proposals.blade.php`'s `renderRemoteTable`+pill pattern), with an "Assign" button per row opening
   `<x-side-modal id="assignOwnersModal">` - two `makeSearchableSelect`-wrapped selects (CSR/RM,
   both sourced from the existing `GET /api/crm/prospects/assignable-users`), saving via
   `POST /api/crm/prospects/{uuid}/assignment` `{assigned_to, relationship_manager_id}` (both
   required server-side).

2. **RFP wizard gating** - `logic_prospect_request_proposal.js`'s `ensureProposalRequestExists()`
   (the old lazy-mint-on-first-save helper) was removed entirely, since the "Request for Proposal"
   button (`#pimRequestProposalBtn` in `logic_prospect_info_modal.js`) now mints the row **eagerly
   on click** (`POST .../proposalRequests`) before ever opening the wizard - the wizard always
   receives a real, known id now. A new `applyAssignmentGating(modal, lead)` helper (called from
   `renderAll()`, so it re-applies on every refresh too) toggles a persistent amber
   `#rfpAwaitingAssignmentBanner` and `.disabled` on 5 mutating buttons
   (`rpmSaveCompanyDetailsBtn`/`rpmAddSignatoryBtn`/`rpmAddOdBtn`/`rpmAddProductBtn`/`rpmSubmitBtn`)
   based on `lead.is_fully_assigned` - a client-side mirror of the backend's 409, not the real guard.
   Note found mid-pass: `#rpmAddOdBtn` already had `disabled:opacity-40 disabled:cursor-not-allowed`
   Tailwind classes on it *before* this pass touched the file (pre-existing, not added by this
   change) - the other 4 buttons didn't, so those were added to match.

3. **Split proposals.blade.php** into two pages sharing one JS file - see
   `resources/js/logic_client_proposals_shared.js` (registered in `app.js`). Both
   `pages/proposals.blade.php` ("Approvals") and NEW `pages/proposal_signed_contracts.blade.php`
   ("Signed / Contracts") set a `window.PROPOSALS_PAGE_MODE` global ('approvals' |
   'signed_contracts') in a tiny inline bootstrap `<script>` before calling
   `window.initClientProposalsPage()` - same "globally-loaded file + page's own bootstrap calls
   `window.initXxx()`" convention already used by `crm.blade.php`/`logic_prospect_modal.js` etc.
   (NOT the old inline-script-per-page style the original unsplit `proposals.blade.php` used -
   that whole ~600-line inline `<script>` became the shared file, self-guarded on
   `#tableClientProposals` since both pages' `<x-table>` share that id).
   - Approvals keeps its original 7-pill strip + decision buttons only (Approve/Disapprove/Reject/
     Cancel) in `#ClientProposalModal`'s footer; default filter unchanged ('all', no pill
     pre-highlighted, same as before).
   - Signed/Contracts gets a pared 3-pill strip (Approved/Accepted/All, default **Approved** -
     `activeStatusFilter = '2'` at init, with that pill's ring highlighted via a new
     `markActivePill()` helper) and owns the signed/download/contract footer buttons
     (`cpmSignedSection`/`cpmDownloadLink`/`cpmCreateContractBtn`/`cpmViewContractBtn`/
     `cpmCreateClientMasterBtn`) plus the entire `createContractModal` markup, moved here verbatim.
   - Since roughly half the DOM ids referenced by the shared JS only exist on ONE of the two pages
     (decision buttons vs. signed/contract buttons; `countPending`/`countDisapproved`/`countRejected`/
     `countCancelled`/`countAwaitingDecision` vs. the 3-pill page's smaller count set), **every
     `document.getElementById(...)` lookup for an optional element is null-guarded** (`toggle()`
     itself does `?.classList`, `updateProposalCounts()` guards each span, every `bindEvents()`
     listener attach uses `?.addEventListener`). If a future pass adds a new button to one page's
     footer only, follow this same null-guard pattern rather than assuming presence.
   - Deliberately did NOT keep Reject/decision buttons on the Signed/Contracts page even though an
     Approved-status proposal can technically still be rejected (`ClientProposal::canBeRejectedBy`) -
     the brief was explicit that each page's footer shows only its own button group, and Approved
     rows are still reachable via the Approvals page's own "Approved" pill for that action.

**Known risk flagged, not fixed** (backend change, out of a frontend-dev task's lane): see the
2026-09-30 update in [[project_prospect_modal_build]] - `ProspectController::saveStage1` silently
nulls `relationship_manager_id` whenever it's omitted from the payload, and neither remaining
caller of that endpoint sends it anymore now that both RM-editing UIs are gone.
