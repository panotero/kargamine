---
name: project-prospect-modal-build
description: Multi-pass build of the 4-tab Prospect modal (prospect-modal.blade.php / logic_prospect_modal.js) replacing the old crmLeadForm.blade.php - gotchas for whoever does the next pass (e.g. Tab 4 / Request for Proposal).
metadata:
  type: project
---

The old full-page CRM lead form (`resources/views/pages/crmLeadForm.blade.php`) was retired and
replaced by a 4-tab `<x-side-modal>` built across multiple passes: `resources/views/components/prospect-modal.blade.php`
+ `resources/js/logic_prospect_modal.js`, opened via `window.openProspectModal(uuid?)`. Pass 1
(pre-2026-09-17) built the modal shell + Tab 1 (Identity). Pass 2 (2026-09-17) built Tab 2
(Contact Information) and Tab 3 (Requirements), tab gating, and full prefill-on-open. Backend
side of Pass 2 (see `.claude/agent-memory/backend-dev/project_prospect_modal_contacts_requirements.md`)
added `prospect_contacts`/`proposal_requests`/`proposal_request_containers`.

**Why this matters for future passes:** the old `crmLeadForm.blade.php` is gone from the working
tree (only recoverable via `git show HEAD:resources/views/pages/crmLeadForm.blade.php`) but its
patterns (CONTAINER_TYPES, TYPE_FIELD_VISIBILITY, the location→port cascade, the currency-input/
DG-upload flow) were the base for Tab 3 and are worth diffing against again if a later pass touches
containers.

**How to apply — gotchas found while building Pass 2:**
- This file's whole IIFE executes exactly once (first script load), but `#ProspectModal`'s DOM is
  torn down and recreated every SPA page load (`window.initProspectModal()` re-runs each time, see
  the file's own header comment and [[feedback_crm_scope_discipline]]'s sibling pattern). Module-
  scope caches (ports/locations/container catalog, LOV option-html strings) are therefore fetched
  **once ever** and "painted" into the fresh DOM on each `initProspectModal()` call - don't add a
  fresh network fetch inside a function that runs per-tab-click or per-card-add; follow the existing
  `ensureContainerLookupsLoaded()` / `applyContainerLookupsToDom()` split.
- `container_class_id`/`container_size_id` on `proposal_request_containers` are independent nullable
  columns (not a `container_variant_id`), so the `"__base__"` no-class sentinel used in
  `crm.blade.php`'s proposal-row builder (Create Proposal UI, off-limits per
  [[feedback_crm_scope_discipline]]) does **not** apply here - an empty-string select value already
  becomes `null` via Laravel's default `ConvertEmptyStringsToNull` middleware. Don't port the
  variant-based sentinel dance into this modal.
- The old `crmLeadForm.blade.php` defined a `convanClass: true` flag in its `TYPE_FIELD_VISIBILITY`
  for CV but never actually rendered a `container_class_id` field anywhere - that half of the old
  form was dead code. The new Tab 3 had to build that select from scratch (sourced the same way as
  the size select, from `/api/containers`'s `classes`/`sizes` per container code).
- The old form's DG-document upload call used `/api/crm/leads/uploadDgDocument`, a stale URL from
  before the `CrmLead` → `Prospect` rename. The live route is `/api/crm/prospects/uploadDgDocument`
  (`ProspectController::uploadDgDocument`) - don't copy the literal URL string out of git history
  without checking `routes/api.php` first.
- Tab 4 ("Request for Proposal") is still a bare placeholder (`<div>content for request proposal</div>`)
  gated on `stage_completion` 1+2+3 all true - whoever builds it next should read
  `ProspectController::show()`'s eager-loads and `Prospect::stageCompletionFlags()` first, same as
  this pass did for Tabs 2/3.
- (2026-09-25 update) Tab 4 is now fully built, as a separate wizard modal opened from
  `ProspectInfoModal`'s Proposal tab, not inline in `#ProspectModal` itself:
  `components/request-proposal-modal.blade.php` + `resources/js/logic_prospect_request_proposal.js`
  (`window.openRequestProposalModal(lead, proposalRequestId)`), plus a standalone
  `components/add-origin-destination-modal.blade.php` + the O&D section of
  `resources/js/logic_prospect_add_modals.js` for appending a single location outside the wizard.
  A `ProposalRequest` row is only minted on first save inside the wizard
  (`ensureProposalRequestExists()`), never on open. `window.prospectInfo` (`logic_prospect_info_modal.js`)
  is the single source of truth for the current prospect payload across all these files - every
  mutating action reloads through it rather than keeping a local copy.
  Endpoint gotcha found in a 2026-09-25 fix pass: the real locations endpoints are the **plural**
  `/api/crm/prospects/{uuid}/proposalRequests/{id}/locations[/​{locId}]` (matching the wizard's own
  add/remove) - `logic_prospect_info_modal.js`'s `removeOdLocation()` had been hitting a stale
  **singular** `/proposalRequest/locations/{id}` (no id segment for the request itself) and 404ing;
  same singular-vs-plural mismatch is worth double-checking if touching
  `logic_prospect_add_modals.js`'s other `proposalRequest/{containers,truckings,charters}` (Add
  Requirement modal) URLs, which are a *different*, intentionally-still-singular endpoint family
  (prospect-owned "requirements", not proposal-request-owned "products" - don't conflate the two).
- (2026-09-17) A separate cleanup pass confirmed the old `crmLeadForm.blade.php`-era CRM flow is
  now fully gone from `crm.blade.php`/`logic_crm.js` too: `LeadInfoModal`, `LeadAddProposalModal`,
  `LeadAddContainerModal`, and every JS function that only existed to power them were removed as
  dead code (nothing linked to them since both "New Prospect" and every table row open
  `<x-prospect-modal>` now) - see [[feedback_crm_scope_discipline]]. Before assuming a "New
  Proposal" or "Add Container" UI exists somewhere for Tab 4 to call into, note that
  `resources/views/components/new-proposal-modal.blade.php` (`id="generateProposal"`) looks like a
  plausible candidate but grepped as orphaned - nothing includes `<x-new-proposal-modal />` or
  calls `initSideModal({modalId: 'generateProposal'})` anywhere. Re-verify that yourself rather
  than assuming it's wired up; Tab 4 will likely need genuinely new markup/JS, not just a call into
  an existing hookup.
- (2026-09-27 update) Backend renamed `delivery_type_id` (an LOV-backed FK) to a plain `service_type`
  string column (exactly `Door - Door`/`Door - Pier`/`Pier - Door`/`Pier - Pier`) across
  `proposal_request_containers` AND every Products-tab table (`product_containers`,
  `product_rolling_cargo`, `product_loose_cargo`, `product_truckings` all gained `service_type`);
  Products-tab `product_containers`/`product_truckings` also gained a plain `quantity` int. Fixed in
  both `logic_prospect_modal.js` (Requirements tab, `#pmDeliveryType`) and
  `logic_prospect_request_proposal.js` (Products tab, `#rpmPcDeliveryType`/`#rpmRcDeliveryType`/
  `#rpmLcDeliveryType`) - both files now define their own local `SERVICE_TYPES` array + a rendered
  `serviceTypeOptionsHtml` string (small intentional duplication, matching this codebase's existing
  "own local cache per file" precedent rather than exporting one shared copy) and POST/read
  `service_type` as a plain string, not an id. **`add-requirement-modal.blade.php` +
  `logic_prospect_add_modals.js` (`#armDeliveryType`, `#armContainerType`, its own separate
  `CONTAINER_TYPES` array with `"Loose Cargo (LC)"`) were NOT touched in this pass** - out of scope
  per that task's named-files list - so that standalone modal still POSTs the old `delivery_type_id`
  shape and still shows the old "Loose Cargo (LC)" label; it will likely fail validation against the
  renamed backend column and is visually inconsistent with the relabeled "Break Bulk Cargo (BB)"
  used everywhere else. Whoever touches that modal next should bring it in line with the same
  `SERVICE_TYPES`/`quantity`/"Break Bulk Cargo (BB)" changes.
- (2026-09-27 follow-up) `add-requirement-modal.blade.php` + `logic_prospect_add_modals.js` were
  brought in line: `#armDeliveryType`'s `data-field` changed `delivery_type_id` → `service_type`
  (its select is populated with a local `SERVICE_TYPES`/`serviceTypeOptionsHtml`, same 4 values,
  defined at the top of `logic_prospect_add_modals.js`'s IIFE - this file didn't previously have
  access to `logic_prospect_modal.js`'s copy, so it's a third independent local copy, matching the
  existing per-file duplication precedent); its local `CONTAINER_TYPES` LC label is now
  `"Break Bulk Cargo (BB)"` (value still `LC`); `containerRowHtml`'s recap cell reads
  `c.service_type` instead of `c.delivery_type?.name`. Confirmed this modal's freight-add payload
  is built generically by `collectArmFormPayload()` off every `[data-field]` in `#armContainerForm`
  (unlike a hand-built object), so the `data-field` rename alone fixed the POST key - no separate
  payload-assembly code to touch. The `quantity`-int addition mentioned above was NOT part of this
  follow-up (not asked for) - if `product_containers`/`product_truckings`-style `quantity` also
  applies to this modal's endpoint, that's still open.
- (2026-09-27) The Confirmation tab / Prospect Info summary modal's Products recap
  (`buildProposalRequestRecapHtml`, exported on `window`, shared by both) was rewritten from
  one-table-per-raw-row-list into grouped, running-numbered line-item tables (Item No./Detail/
  Route/Qty), merging rows that share a (product type [+subtype] + route) key with quantity summed
  - Charter rows are the one exception, never merged. Groups are keyed by the row's own
  CV/FR/RF/RC/LC/TR/CH type code (not one merged "Containers" table for all three container
  subtypes) - each gets its own colored dot, reusing `logic_prospect_modal.js`'s
  `CONTAINER_TYPE_DOT_COLOR` convention (duplicated locally as `LINE_ITEM_TYPE_META`, plus new
  colors invented for TR/CH since no prior mapping existed for those). If a future pass needs the
  old flat-recap-per-product-type shape back, it's fully gone from this file (not commented out).
- (2026-09-30 update — Management assignment flow) `relationship_manager_id` is **no longer set
  from either the Identity tab or the Prospect Info modal's inline Contact Summary editor** - both
  pickers (`#pmRelationshipManager` in `prospect-modal.blade.php`,
  `#pimEditRelationshipManager` in `prospect-info-modal.blade.php`) were removed, along with every
  JS reference (`fillRelationshipManagers()`, draft-snapshot fields, etc.). It's now set
  exclusively via Management's new assignment queue page (`/page_proposal_requests`,
  `logic_proposal_requests.js`'s `assignOwnersModal`, `POST /api/crm/prospects/{uuid}/assignment`
  with `{assigned_to, relationship_manager_id}` both required) - see
  `.claude/agent-memory/frontend-dev/project_proposal_assignment_and_signed_contracts_split.md`.
  The Contact Summary card still *displays* the current RM read-only (`lead.relationship_manager?.name`).
  **Known risk (not fixed, out of frontend-dev's lane):** `ProspectController::saveStage1` does
  `'relationship_manager_id' => $data['relationship_manager_id'] ?? null` - since neither of the
  two remaining callers of `POST /crm/prospects/stage1` (`saveIdentity()` in `logic_prospect_modal.js`,
  `saveSummaryEdit()` in `logic_prospect_info_modal.js`) send this field anymore, **every Identity-tab
  save or Contact-Summary edit silently wipes out whatever RM Management already assigned**, since
  the controller doesn't fall back to the existing value when the key is omitted. This was flagged
  in the report, not fixed (backend controller change, out of a frontend-dev task's lane) - whoever
  picks this up next should either have `saveStage1` fall back to `$lead->relationship_manager_id`
  when the key is absent, or have the two frontend callers explicitly resend
  `lead.relationship_manager_id` unchanged (mirroring what `removeLocation()` in
  `logic_prospect_info_modal.js` already does correctly).
- (2026-10-05 update — "Proposals & Activity" tab added to `ProspectInfoModal`) New first tab in
  `PIM_TABS`/`#pimTabBar` (`data-pim-tab="Proposals"` / `data-pim-pane="Proposals"`), now the
  modal's default-open tab (`openProspectInfoModal` calls `switchPimTab(modal, "Proposals")`
  instead of `"Locations"`). Two static sections rendered by new functions
  `renderRequestedProposals`/`renderActivityTimeline`, called from `renderAll`, purely off fields
  already present on the `GET /api/crm/prospects/{uuid}` payload (`lead.proposal_requests[].client_proposals`,
  `lead.activities`) - no new endpoints. `RFP_STATUS_PILL` here deliberately includes a `pending`
  entry that `logic_proposal_requests_mine.js`'s sibling `STATUS_PILL` omits, since this list shows
  just-minted/not-yet-assigned requests that that other queue never displays - don't "simplify" by
  re-sharing one copy without re-adding pending. PDF/signed-doc download links are plain `<a href>`
  tags (native browser download, no `apiCall`), gated on `client_proposals[].status` 2/4 for the PDF
  link and status 4 + a regex-validated `signed_document_path` for the signed-copy link, mirroring
  `logic_client_proposals_shared.js`'s existing gating.
- (2026-10-05 update — "Copy from Prospect Products" shortcuts on the RFP wizard's Products tab)
  `request-proposal-modal.blade.php` + `logic_prospect_request_proposal.js` gained a button list,
  above the `#rpmProductSelect` picker, of the prospect's own intake-time requirement rows
  (`rpmLead.requirement_containers/_truckings/_charters` — a different, frozen-shape set from this
  wizard's own saved `product_*` rows). Clicking one is a prefill shortcut only: `applyProductPrefill()`
  switches `#rpmProductSelect` to the right pane and calls the new generic `fillFormFromData(formSelector,
  data)` (inverse of the existing `collectFormPayload`) with a small per-type `data` object built by
  `containerPrefillData`/`rollingOrLoosePrefillData`/`truckingPrefillData`/`charterPrefillData` — never
  submits, never touches origin/destination (requirement rows' origin/destination are master `locations`
  IDs, a different table/ID space from the product forms' `prospect_locations`-backed selects — mapping
  them is impossible, don't attempt it again), never touches charter cargo items/ports (those are
  sub-items keyed on an already-saved charter id, can't be pushed into an unsaved form). Container
  prefill has an ordering requirement — set `#rpmPcContainerType` + call
  `applyPcContainerTypeVisibility`/`populatePcContainerSizeOptions` *before* `fillFormFromData` (so the
  size `<select>`'s options exist when `container_size_id` is set) — and must re-run
  `applyDispatchModeGating` *after* filling, since `service_type` is now prefilled and the dispatch
  selects' disabled state needs to follow it. `requirement_containers` rows carry `container_type`
  CV/FR/RF/RC/LC all in one shape (`ProposalRequestContainer`) — RC/LC route to the Rolling/Loose Cargo
  panes, not the Container pane. Backend: zero changes, this was frontend-only against fields already
  present on the existing `GET /api/crm/prospects/{uuid}` payload.
- (2026-10-05 update — Rate matrix + T&C now persist for real) Built to a locked API contract while
  backend built the endpoints in parallel (`.../proposalRequests/{id}/productRates` and
  `.../termsAndConditions`, both POST, both added to `pr.product_rates`/`pr.ancillary_fees`/
  `pr.terms_of_payment_standard`/`_charter` on the lead payload). `saveProductSpecification()` (Build
  Rate Matrix tab's per-product modal) now actually POSTs instead of just toasting - reads fee rows
  LIVE off `#psmRateBreakdownBody` DOM (not `sampleFeeBreakdown()`, since the reviewer may have edited
  adjustments), via a new module var `rpmMatrixCurrentRow` stashed in `openProductSpecificationModal()`
  (parallel to the existing `rpmMatrixCurrentKey`). Reopening the modal now rehydrates the fee table from
  the product's saved `product_rates` row if one exists (`savedFeeBreakdown()`), falling back to
  `sampleFeeBreakdown()` only when nothing's saved yet. The Rate check/warning icon (`matrixRateIcon`) is
  now OR'd from two signals - the session-only `rpmMatrixConfirmedKeys` Set (empty on every fresh open)
  and a new `hasPersistedRate(pr, row)` check against `pr.product_rates` - so reviewed-state survives a
  reload; `matrixRowHtml`/`matrixRecapRowHtml` were changed to take `confirmed` as a param (computed by
  the two callers, `renderBuildMatrix`/`buildRateMatrixRecapBlock`) rather than each computing it
  internally, to keep both OR'd checks in exactly one place.
  For Terms & Conditions: the T&C pane's 20 "sample fee" ancillary `<input>`s (5 reference tables x 4
  rows each: Trucking/Storage/Loading-Unloading/Documentation) gained `data-section`/`data-service` + a
  shared `.rpm-anc-fee` class (section = the block's exact heading string, e.g. `"For ConVans &
  Flatracks"`) - `saveTermsAndConditions()` collects one `{section, service_name, fee_amount}` row per
  non-blank `.rpm-anc-fee` input (via `window.parseCurrencyValue`), skipping blanks entirely per the
  brief. The two Terms of Payment `<select>`s (`#rpmTcTermsOfPaymentStandard`/`#rpmTcTermsOfPaymentCharter`)
  persist directly. A new `renderTermsConditions(modal, lead)` (called from `renderAll`) hydrates both
  the selects and the 20 inputs from the saved payload on every open/refresh - confirmed
  `window.formatCurrencyDisplay` exists (`resources/js/customFunctions.js`) before using it to format
  the rehydrated fee values. The other ~15 display-only inputs in that pane (declared value caps,
  surcharge %, storage days, load limits, temperature, laytime) were deliberately left alone - not part
  of the contract, brief said display-only.
  On the Approvals/Signed-Contracts side (`logic_client_proposals_shared.js`, shared by both pages per
  [[project_proposal_assignment_and_signed_contracts_split]]), `openProposalModal()`'s rates table is
  now a branch on `p.proposal_request_id && p.proposal_request?.product_rates?.length` - rather than
  reshape one table's columns in JS, added a SECOND static `<table id="cpmRfpRatesTable">` (Item No. |
  Product | Base | Adjustment | Total, each row's `fee_breakdown` lines as an indented sub-row) next to
  the existing `#cpmLegacyRatesTable`, and JS just toggles which one is visible - keeps the "static HTML
  for fixed structure" convention intact for both shapes instead of fighting it. Added this table plus a
  new `#cpmRfpTerms` section (Terms of Payment + ancillary fees grouped by section) to BOTH
  `pages/proposals.blade.php` and `pages/proposal_signed_contracts.blade.php` identically, since both
  pages render their own independent copy of `#ClientProposalModal` (confirmed by reading both files -
  this is NOT a shared partial, each page's Blade already has its own full copy of the modal markup,
  pre-existing duplication this task matched rather than introduced). The legacy `p.rates` branch is
  byte-for-byte what it was before this change. Didn't touch `createContractModal`/Create Contract logic
  in the same file (out of scope, still reads `p.rates` only) - worth flagging if contracts ever need to
  originate from RFP-sourced proposals too.
- (2026-10-20 update — ownership/edit-rights gating + Reassign Owner) Backend added two booleans to
  the `GET /api/crm/prospects/{uuid}` payload - `can_edit` (owner/in-reach team leader/superadmin)
  and `can_reassign` (team leader/superadmin) - plus an eager-loaded `owner: {id, name, team_id}`
  and `POST /api/crm/prospects/{uuid}/reassign-owner` (`{owner_id}`). Frontend-only pass (this one):
  `prospect-modal.blade.php` gained `#pmViewOnlyBanner` (full-width, sits between the header and the
  rail+content body since this modal's tab nav is a *vertical rail* - not a horizontal bar like RPM's
  `#rfpAwaitingAssignmentBanner` - so "just under the tab bar" was interpreted as "just above the
  rail+content row" to stay visible across every tab) + `applyProspectEditGating(modal, lead)` in
  `logic_prospect_modal.js`, mirroring `applyAssignmentGating()`'s disabled-id-list + banner-toggle
  shape. Gated ids: `pmSaveIdentityBtn`/`pmAddLocationBtn`/`pmSaveContactsBtn`/`pmAddContactBtn`/
  `pmAddFreightBtn`/`pmAddTruckingBtn`/`pmAddCharterBtn`, plus already-rendered row deletes
  `.pm-remove-location-row`/`.pm-remove-contact-row`/`.pm-remove-container-row`/
  `.pm-remove-trucking-row`/`.pm-remove-charter-row` (verified via grep - the *card's own* remove
  button, `.pm-remove-location`/`.pm-remove-contact`, was deliberately left out of the gated selector
  since its card can only ever be reached through the already-gated Add button, so it can never be
  open in read-only mode). Added a reset call `applyProspectEditGating(modal, null)` right after the
  existing `updateTabGating(modal, {})` reset at the top of `openProspectModal()` (not explicitly
  asked for, but necessary - without it, reopening this one persistent modal instance for a brand-new
  prospect right after viewing a read-only existing one would leave every Add button permanently
  disabled, since otherwise `applyProspectEditGating` is only ever called from the existing-lead
  success branch).
  `prospect-info-modal.blade.php`/`logic_prospect_info_modal.js`: `#pimSummaryEditBtn` now hidden
  when `lead.can_edit === false`; new header-rail `#pimReassignBtn` (shown only when
  `lead.can_reassign === true`) opens a new single-select `<x-side-modal id="reassignOwnerModal">`
  added to `crm.blade.php` right after `<x-prospect-info-modal />` - a trimmed one-select copy of
  `proposal_requests.blade.php`'s `assignOwnersModal`/`openAssignModal`/`saveAssignment` idiom
  (own local `assignableUsersOptionsHtml` cache + `refreshSearchable()` helper, both copied into
  `logic_prospect_info_modal.js` since it didn't have them yet - reuses the already-existing
  `GET /api/crm/prospects/assignable-users` endpoint, no new list endpoint needed). After save,
  reloads in place via the existing `reloadProspectInfo(uuid)` and also calls the real existing CRM
  table reload hook `window.reloadCrmData?.()` (found by grepping `logic_crm.js` - the table itself
  is a function-local `const table`, not a global handle; `window.reloadCrmData` is the one thing
  that *is* exported, wrapping `renderTable().load(1)` + `renderCounts()`). Zero backend/controller
  work done in this pass (out of lane) - this was built against the locked contract while a backend
  pass added the two booleans + endpoint in parallel, not yet independently verified against a live
  response.
- (2026-10-20 update — Build Rate Matrix: real freight base rate for Container rows, Ancillary
  popup pricing preview dropped) Two small independent changes in the same file. (1) The Add
  Ancillary popup's live Base Value/Subtotal preview (`updateAncillaryPreview()`, the
  `#asmBaseValuePreview`/`#asmSubtotalPreview` `<p>`s, and the table's Base Value/Subtotal columns)
  was removed entirely from `ancillary-services-modal.blade.php` - that tab now only ever captures
  qty/unit/remarks, matching the brief's "pricing deferred to Build Rate Matrix" framing; nothing
  else in `addAncillary()`'s payload changed (it never sent base_value/total_value - backend already
  computes those). (2) `openProductSpecificationModal(type, id)` is now `async` (its one caller, the
  row-click listener near line ~1966, doesn't await it - fire-and-forget is fine here since it only
  drives modal population). New branch: when `savedFeeBreakdown(pr, row)` is null AND `type ===
  "container"`, it now awaits a new `containerFreightFeeBreakdown(row)` helper that GETs
  `/api/crm/prospects/{uuid}/proposalRequests/{id}/products/containers/{row.id}/rate` (envelope
  `{success, data: {frt, is_door_origin}}`) and builds `[{detail: "Base Freight Charge", base: frt||0,
  adjustment: 0}, …optionally "Trucking Fee (Door - Origin)" at base 0 if is_door_origin === true]` -
  non-container types (rolling_cargo/loose_cargo/trucking/charter) are untouched, still
  `sampleFeeBreakdown()`. This is the first real (non-placeholder) rate source in the Build Rate
  Matrix tab; if a later pass extends real rates to rolling_cargo/loose_cargo/trucking/charter,
  expect a parallel `{frt-equivalent, is_door_origin-equivalent}` contract per type and follow this
  same `savedFeeBreakdown ?? real-fetch-by-type ?? sampleFeeBreakdown` branching shape rather than
  inventing a new one.
