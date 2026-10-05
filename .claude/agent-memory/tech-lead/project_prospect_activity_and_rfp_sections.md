---
name: prospect-activity-and-rfp-sections
description: Where prospect requested-proposals + activity-log live, and that ActivityService had no live call sites before this work
metadata:
  type: project
---

Added two sections to the **post-assignment prospect detail modal** (`prospect-info-modal.blade.php` + `logic_prospect_info_modal.js`, driven by `window.openProspectInfoModal`): a "Requested Proposals" list and an "Activity & History" timeline.

**Why:** give CSR/RM a single place to see every RFP minted for a prospect (with inline PDF download for approved/accepted linked ClientProposals) and a chronological lifecycle log.

**How to apply / key facts for future work:**
- The intake modal (`prospect-modal.blade.php`, 3 tabs Identity/Contact/Requirements) is DIFFERENT from the detail modal (`prospect-info-modal.blade.php`, tabs Locations/Contacts/Requirements/OriginDestination + 19rem right rail with the "Request for Proposal" button). CLAUDE.md's "4-tab modal" prose is stale. The two sections live INLINE in the 19rem right rail (`#pimContactSummary`), stacked directly BELOW the "Request for Proposal" button — NOT a tab. An earlier attempt put them in a new "Proposals & Activity" tab; the product owner explicitly rejected that, so the default main tab is now Locations. Render functions target `#pimRequestedProposals`/`#pimActivityTimeline` by id regardless of DOM location, so moving them needed no render-logic change.
- `GET /api/crm/prospects/{uuid}` (`ProspectController::show`) already eager-loads `proposalRequests.*`, `clientProposals`, and `activities.user`. Display needs no new endpoint — just enhanced eager loads.
- `ProposalRequest` has NO uniqueness on prospect_id; multiple requests per prospect are normal (see `destroyProposalRequest` comment). Statuses: pending → assigned → for_approval → approved/signed (set by ClientProposalController) / cancelled.
- `App\Services\ActivityService::create($prospectId,$type,$description,$createdBy=null)` writes `prospect_activities`. Before this work it had ZERO live call sites for lifecycle events (only the legacy maintenance `ProposalController@line240` used it). Lifecycle logging call sites were ADDED at: prospect created, RFP requested, CSR/RM assigned, RFP submitted/cancelled, ClientProposal created/approved/disapproved/rejected/cancelled/signed. Existing prospects have no backfilled history — feed starts from when logging was added.
- Download artifact = generated proposal PDF via `GET /api/clientProposals/{id}/pdf` (binds by id), shown for linked ClientProposal status 2/4 — matches the Proposals page pattern (`logic_client_proposals_shared.js`). A "Download Signed Copy" link also renders for status 4 when `signed_document_path` is a string matching `/^(\/|https?:\/\/)/` — see the suspected bug below re: whether that path is ever valid.
- SUSPECTED PRE-EXISTING BUG (not fixed here): `ClientProposalController::attachSigned` assigns `signed_document_path` the ARRAY returned by `FileUploadService::uploadFile()`, but the column has no array cast — likely stores "Array"/garbage. Flag before relying on signed-doc URLs.
