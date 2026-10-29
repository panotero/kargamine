# FUNCTION_MAPPING.md

A stage-by-stage trace of the app's core lifecycle chains — Model method(s), Controller
action + route, and frontend JS function(s)/file(s) for each step — so a given feature can
be followed end-to-end as a path, not hunted for file by file.

**Keep this in sync.** Whenever a task touches a function that appears in a chain below (or
adds a new stage to one), update its entry here in the same change. When you map a new
module's chain, add a new top-level section for it — this file currently seeds only the
**CRM → Proposal** chain (other modules to be mapped later). Pair this with `MODULES.md`
(which catalogs modules/routes/gating) and `VISUALS.md` (UI patterns); this file is the
"how the pieces connect" view.

Route prefixes below: `/api/crm/...` = the `crm` group in `routes/api.php`; `/api/...`
(e.g. `/api/proposalRequests`, `/api/clientProposals`) = top-level `routes/api.php`.

---

## Chain A: CRM Prospect → Client Proposal

The commercial path from a brand-new prospect to a signed proposal. Entities:
`Prospect` → `ProposalRequest` (RFP) → `ClientProposal` → (boundary) `ClientContract`.

### A1. Prospect creation
- **Model:** `Prospect` (`HasUuids`, `uniqueIds() => ['uuid']`; internal `id` for FKs, public `uuid` in routes). `Prospect::saveStage1`/`saveStage2`, `stageCompletionFlags()` (identity / contacts / requirements gates), `recomputeCompletion()`.
- **Controller + route:** `ProspectController::saveStage1` — `POST /api/crm/prospects/stage1` (create sets `created_by` = `owner_id` = current user; starts genuinely unassigned). Contacts: `saveContacts` — `POST /api/crm/prospects/{uuid}/contacts`. Requirements (prospect-owned products): `storeProposalRequestContainer`/`storeProposalRequestTrucking`/`storeProposalRequestCharter` (+ deletes).
- **Frontend:** `resources/js/logic_prospect_modal.js` (4-tab intake modal, `components/prospect-modal.blade.php`); read-only view in `logic_prospect_info_modal.js` (`components/prospect-info-modal.blade.php`).
- **Connects to:** A2 (who may edit it) and A3 (minting the RFP row).

### A2. Prospect ownership / edit rights
- **Model:** `Prospect::canBeEditedBy($user)` (creator/owner + in-reach team leader tier), `Prospect::canBeReassignedBy($user)` (team-leader-only). Ownership column: `prospects.owner_id`. Visibility helper: `TeamService::accessibleTeamUserIds($user)` / `accessibleUserIds($user)`.
- **Controller + route:** `ProspectController::authorizeProspectEdit()` (throws 403 `HttpResponseException`; called at the top of every prospect-mutating action). `ProspectController::reassignOwner` — `POST /api/crm/prospects/{uuid}/reassign-owner`. `ProspectController::index` scopes the CRM list by `created_by`/`owner_id`/`assigned_to` ∈ `accessibleTeamUserIds` (superadmin bypass). `show()` appends `can_edit`/`can_reassign`.
- **Frontend:** `logic_prospect_info_modal.js` / `logic_prospect_modal.js` read `can_edit`/`can_reassign` off the prospect payload to gate Edit/reassign UI.
- **Connects to:** A4 (the SAME `accessibleTeamUserIds` scope governs who sees a row on the combined Requests page) and A5 (the assign action's role gate).

### A3. ProposalRequest (RFP) creation
- **Model:** `ProposalRequest` (`status`: pending → assigned → for_approval → approved → signed, or cancelled; `code` `RQ-YY-XXXXXX` via `generateNextCode()`). `Prospect::proposalRequests()` (hasMany), `Prospect::proposalRequest()` (singular `latestOfMany()`, "the one being worked on").
- **Controller + route:** `ProspectController::storeProposalRequestNew` — `POST /api/crm/prospects/{uuid}/proposalRequests` (mints status `pending`, gated by `authorizeProspectEdit` — the ownership tier, NOT the later assigned_to match). `destroyProposalRequest` — `DELETE .../proposalRequests/{proposalRequestId}`.
- **Frontend:** "Request for Proposal" trigger in the Prospect Info modal (`logic_prospect_info_modal.js`).
- **Connects to:** A4 (Management assigns the freshly-minted `pending` request).

### A4. Assignment (CSR + Relationship Manager)
- **Model:** reads/writes `prospects.assigned_to` (CSR) + `prospects.relationship_manager_id` (RM); flips the prospect's `pending` ProposalRequest(s) to `assigned`. `Prospect::isFullyAssigned()` gates the RFP wizard endpoints (409 until both set, via `AuthorizesProposalRequestAccess`).
- **Controller + route:** `ProspectController::assignOwners` — `POST /api/crm/prospects/{uuid}/assignment`. **Management-only**, gated in-method by `abort_unless(RoleHelper::hasAnyRole($user, ['superadmin','admin']), 403)` as the first statement (NOT nav/permission-gated — the combined Requests page is now broadly visible). Notifies the assigned CSR/RM via `TeamNotifier` with a `{modal_fn: 'openMyProposalRequestByCode', modal_args: [code]}` deep-link.
- **Combined Requests list:** `ProposalRequestAssignmentController::index` — `GET /api/proposalRequests` — lists every `ProposalRequest` with a unified `ProposalRequest::getEffectiveStatusAttribute()` + `latestClientProposal()` relation; team-scoped per A2; per-row `can_assign`/`can_open_my_request`. (`mine()` — `GET /api/proposalRequests/mine` — is the self-scoped "My Requests" workspace that also hosts the RFP wizard.)
- **Frontend:** `resources/js/logic_proposal_requests.js` (combined page `pages/proposal_requests.blade.php`): row-click opens the read-only `proposal-request-info-modal` (Assign shown when `can_assign`; "Open Request" cross-link when `can_open_my_request`, calling `window.openMyProposalRequestByCode(code)` in `logic_proposal_requests_mine.js`), or the `#ClientProposalModal` when a `latest_client_proposal` exists.
- **Connects to:** A5 (the assigned CSR/RM fills out and submits the RFP wizard).

### A5. RFP submission → ClientProposal mint
- **Model:** `ProposalRequest` product tables (`proposal_request_product_*`), rate matrix (`proposal_request_product_rates`), T&C (`proposal_request_ancillary_fees` + `terms_of_payment_*`). On submit, mints a lead-scoped `ClientProposal` (`prospect_id` set, `client_id` null, `proposal_request_id` set, `status` `STATUS_PENDING`=1, code `CPR-YYYYMM-NNNN`).
- **Controller + route:** `ProspectController::submitProposalRequest` — `POST /api/crm/prospects/{uuid}/proposalRequests/{proposalRequestId}/submit` (legal only from `assigned`; requires company details + ≥1 signatory + ≥1 O&D location; sets request → `for_approval` and mints the `ClientProposal` in the same transaction). Product/rate/T&C endpoints live on `ProposalRequestProductController` (`/products/*`, `/productRates`, `/termsAndConditions`).
- **Frontend:** `resources/js/logic_prospect_request_proposal.js` (`components/request-proposal-modal.blade.php`, opened from My Requests).
- **Connects to:** A6 (the minted `ClientProposal` enters the approval chain).

### A6. RM approval (step 1 of 2)
- **Model:** `ClientProposal::canBeApprovedByRm()` — only the deal's assigned RM (`prospects.relationship_manager_id`) holding `proposal.approve.rm`. Advances status 1 (`STATUS_PENDING`) → 7 (`STATUS_PENDING_MANAGER`); records `rm_approved_by`/`rm_approved_at` (NOT `decided_by`/`decided_at`). `canBeApprovedBy()` dispatches on status.
- **Controller + route:** `ClientProposalController::approve` — `POST /api/clientProposals/{proposal}/approve`. `show()` — `GET /api/clientProposals/{proposal}` — appends `can_approve`/`can_reject`/`can_cancel`/`can_upload_signed`.
- **Frontend:** `resources/js/logic_client_proposals_shared.js` — `openProposalModal(id)` renders `#ClientProposalModal` and toggles buttons by `[status]` + the server `can_*` flags; `bindEvents()` (exposed as `window.bindClientProposalModalEvents`) wires the actions. Reused verbatim by the combined Requests page.
- **Connects to:** A7 (forwarded to Manager).

### A7. Manager approval (step 2 of 2)
- **Model:** `ClientProposal::canBeApprovedByManager()` — any holder of `proposal.approve.manager` (NOT deal-scoped). Terminal Approve → status 2 (`STATUS_APPROVED`); sets `decided_by`/`decided_at`/`decision_remarks`. Disapprove=3, Reject=5 (`reject_roles` + client's Sales Rep), Cancel=6. superadmin bypasses both A6/A7.
- **Controller + route:** `ClientProposalController::approve`/`disapprove`/`reject`/`cancel` — `POST /api/clientProposals/{proposal}/{action}`. `approve()` syncs the linked `ProposalRequest.status` → `approved`.
- **Frontend:** same `logic_client_proposals_shared.js` modal path as A6.
- **Connects to:** A8 (signing an approved proposal).

### A8. Signing
- **Model:** `ClientProposal::attachSigned()` — status 2 → 4 (`STATUS_ACCEPTED`); stores `signed_document_path`/`signed_at`; syncs the linked `ProposalRequest.status` → `signed`.
- **Controller + route:** `ClientProposalController::attachSigned` — `POST /api/clientProposals/{proposal}/attachSigned` (multipart). `downloadPdf` — `GET /api/clientProposals/{proposal}/pdf`.
- **Frontend:** `logic_client_proposals_shared.js` — `#cpmSignedSection` / `cpmUploadSignedBtn` (shown when status 2 AND `can_upload_signed`).
- **Connects to:** A9 — the boundary where this map currently stops.

### A9. Contract creation (boundary — not yet mapped)
- **Controller + route:** `ClientContractController::createFromProposal` — `POST /api/clientProposals/{proposal}/contract` (gated by `contract.create` permission), from an Accepted proposal. The downstream `ClientContract` lifecycle (approve/terminate/PDF, `/page_contracts`) is out of scope for this chain so far — map it when a task next touches it.
