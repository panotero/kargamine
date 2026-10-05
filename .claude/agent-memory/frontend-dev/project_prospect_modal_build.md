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
