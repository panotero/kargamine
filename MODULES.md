# MODULES.md

Catalog of every module and submodule in this app, kept in sync with the codebase so the **Roles & Permissions** system (`permissions` table, `role_permission` pivot, `RolesController`, `App\Support\PermissionHelper`, `permission:` middleware) has something authoritative to be scoped against.

**Maintenance rule** (see also `CLAUDE.md`): whenever a module, submodule, or a meaningful new action/route is added to the app, add it here first. Whenever this file changes, review whether the `permissions` table needs new rows for the new action(s), and whether existing roles should be granted them — see "Keeping this in sync with Roles & Permissions" at the bottom.

Each module lists: the page route(s) that surface it, the controller(s) behind it, its submodules/actions, and which of those actions are already permission/nav-gated (so it's obvious at a glance what's covered and what isn't).

---

## 1. Dashboard
- **Route:** `/page_dashboard` → `pages.dashboard`
- **Controller:** `PageController::page_dashboard`, `DashboardController::summary` (`GET /api/dashboard/summary`)
- **Submodules:** summary widgets only, no mutating actions.
- **Gating:** none (read-only).

## 2. CRM (Prospects)
- **Route:** `/page_crm` → `pages.crm`. Two modals, chosen per-row by `openProspectRecord()` (`resources/js/logic_prospect_modal.js`) based on `Prospect::stageCompletionFlags()`:
  - **Incomplete prospect / "New Prospect"** → the **3-tab FORM modal** (`components/prospect-modal.blade.php` + `logic_prospect_modal.js`) — Identity/Contact Information/Requirements, all freely editable. There is no separate page form (the old `/page_crmLeadForm` was retired).
  - **Complete prospect** (all three tabs' gates true) → the **read-only, centered "Prospect Info" modal** (`components/prospect-info-modal.blade.php` + `resources/js/logic_prospect_info_modal.js`, `<x-modal>` not `<x-side-modal>`, sized like the old Lead Info modal) — a persistent right-column "contact summary" card (source/name/business type/CSR/relationship manager, plus a **"Request for Proposal"** trigger at the bottom - see below) alongside tabs: Locations, Contacts, Requirements, **Origin & Destination** (conditionally visible - hidden entirely until the Proposal Request has at least one saved location, see below). An **Edit** button closes this and reopens the side-modal form for the same prospect - all mutation (adding another contact/location/requirement) happens there, not here. Each of Locations/Contacts/Requirements/Origin & Destination has its own dedicated "+ Add ..." modal (`AddLocationModal`/`AddContactModal`/`AddRequirementModal`/`AddOriginDestinationModal`, all in `logic_prospect_add_modals.js`) plus per-row Remove buttons - never a reopened form.
- **Controller:** `ProspectController`, `CrmStatusController`, `CrmActivityController`, `CrmNoteController`
- **Submodules (form-modal tabs; the info modal is a read-only view of the same data, restructured into its own tab layout, see above):**
  - **Identity** — source (encoded string, e.g. `Referral - <name>`), company/prospect name, business type, and locations (`prospect_locations`, PSGC-assisted province→city→barangay + postal autofill). `POST /crm/prospects/stage1`. **Relationship Manager (`relationship_manager_id`) is no longer set here** — it moved exclusively to Management's assignment queue (see section 3(a) below, `ProspectController::assignOwners`); the Identity tab's picker and the read-only info modal's inline Contact Summary editor for it were both removed. The Contact Summary card still *displays* the current CSR/Relationship Manager read-only.
  - **Contact Information** — repeatable contact persons (`prospect_contacts`) with multiple channels (`prospect_contact_channels`: mobile/landline/email × personal/business) and location links (`prospect_contact_addresses`). Replace-all `POST /crm/prospects/{uuid}/contacts`.
  - **Requirements** ("prospect products") — Freight/Trucking/Charter rows owned **directly by the prospect** (`prospect_id`, via `Prospect::requirementContainers()`/`requirementTruckings()`/`requirementCharters()`), each with its own table (hidden until its first row is added): **Container** (`proposal_request_containers` — CV/FR/RF sized by `container_size_id`, RC/LC priced by Revenue Ton instead; Service Type = `delivery_type_id` off the app-wide Delivery Types setting; Origin/Destination are plain `locations`, not ports), **Trucking** (`proposal_request_truckings` — pickup/delivery only, `trucking_cargo_type` off its own LOV group, single/tandem dispatch mode), and **Charter** (`proposal_request_charters` — one row per whole-vessel booking, each owning a repeatable `proposal_request_charter_cargo` list and an ordered `proposal_request_charter_ports` list, port-level not location-level). `POST|DELETE /crm/prospects/{uuid}/proposalRequest/{containers,truckings,charters}[/{id}]`. **Deliberately independent of Proposal Requests below** - having Requirements (which is what gates OPPORTUNITY promotion, `stageCompletionFlags()` gate 3) does not mean a proposal has been requested, and adding/removing a Requirement never touches, creates, or deletes any Proposal Request row.
  - **Request for Proposal trigger** (Contact Summary card, info modal only) — a prospect can have **multiple Proposal Requests over time** (`Prospect::proposalRequests()` hasMany; `status` = `pending`|`assigned`|`for_approval`|`approved`|`signed`|`cancelled`, an end-to-end pipeline status synced from the linked `ClientProposal` - see §3(d) below for the full lifecycle and the wizard that fills each request out). This card's **"Request for Proposal"** button is the TEMP CSR's entire involvement: it mints the `ProposalRequest` row **eagerly, on click** (`POST .../proposalRequests`, `storeProposalRequestNew`, status `pending`) and shows a confirmation toast - that's it, no wizard opens here. `Prospect::proposalRequest()` (singular) is a convenience `latestOfMany()`-scoped accessor meaning "the one currently being worked on" (`status` pending or assigned), used by the Prospect Info modal's own Origin & Destination tab/`AddOriginDestinationModal` (NOT by Requirements above, which never touches Proposal Requests at all).
  - Notes, activities, DG document upload; legacy containers (`/crm/prospects/{uuid}/containers`, frozen — writes to `prospect_containers_legacy`).
  - Team-hierarchy visibility (`TeamService::accessibleTeamIds`) — a rep sees only their own prospects, a team leader sees their subtree's prospects.
- **Gating:** none via `permission:`/`nav.access:` for the CRM prospect CRUD/visibility above — that stays scoped by `TeamService` team hierarchy, not a permission key. However, the **Request for Proposal wizard endpoints** (Proposal Request company details/signatories/Origin & Destination locations/submit/cancel on `ProspectController`, plus every product endpoint on `ProposalRequestProductController`) are now gated by the `App\Http\Controllers\Concerns\AuthorizesProposalRequestAccess` trait: a **403** if the caller isn't superadmin or the prospect's assigned CSR (`assigned_to`)/Relationship Manager (`relationship_manager_id`), and a **409** until Management has assigned both (`Prospect::isFullyAssigned()`). `storeProposalRequestNew`/`destroyProposalRequest` (mint/delete the request itself) only get the 403 ownership check, not the 409 assignment gate, since minting a request is what triggers Management's assignment in the first place. The Requirements-tab prospect-product endpoints (`storeProposalRequestContainer`/`storeProposalRequestTrucking`/`storeProposalRequestCharter`/etc., and `saveStage1`/`saveStage2`/`storeContainer`/`saveContacts`) remain deliberately ungated — out of scope for this change.
  - **No new `permissions` table row was added for this feature.** Management-only actions (the assignment queue list and `ProspectController::assignOwners`) are instead gated by `nav.access:/page_proposal_requests`, matched against a fixed 2-role set (`allowed_roles: ['1','2']` = superadmin/admin) on that nav_menus row — a deliberate choice, not an oversight, so don't "fix" this later by also adding a `permissions` row for the same gate.

## 3. Proposals (Client Proposals)
Restructured into three separate pages/sub-modules (previously one page):

- **(a) Request** — `/page_proposal_requests` → `pages.proposal_requests`. Management's assignment queue: lists every `ProposalRequest` (`ProposalRequestAssignmentController::index`, not team-scoped — Management sees everything) and lets Management set the prospect's CSR/Relationship Manager (`ProspectController::assignOwners`, `POST /api/crm/prospects/{uuid}/assignment`) so the RFP wizard unlocks (see section 2 above). A TEMP CSR triggers the underlying `ProposalRequest` row via `storeProposalRequestNew` (mints immediately with status `pending`, notifies Management via `TeamNotifier`); `submitProposalRequest` also notifies Management once a request reaches `for_approval`. Gated by `nav.access:/page_proposal_requests` (roles 1/2 = superadmin/admin).
- **(b) Approvals** — `page_proposals` → `pages.proposals` (unchanged route/view). `ClientProposalController` approve/disapprove/reject/cancel + rates. Approval is now a **two-step, permission-gated chain** (replacing the old team-leader-pyramid check): a proposal starts at `STATUS_PENDING` ("Pending RM Approval"), where only the deal's own assigned Relationship Manager (`prospects.relationship_manager_id`) holding the `proposal.approve.rm` permission can approve — `ClientProposal::canBeApprovedByRm()` — which forwards it to `STATUS_PENDING_MANAGER` ("Pending Manager Approval") without touching `decided_by`/`decided_at` (recorded separately in `rm_approved_by`/`rm_approved_at`). From there, anyone holding `proposal.approve.manager` (not deal-scoped — `ClientProposal::canBeApprovedByManager()`) makes the final Approve/Disapprove/Reject/Cancel call; `decided_by`/`decided_at`/`decision_remarks` always reflect whichever stage made the terminal decision. `canBeApprovedBy()` dispatches on `$status` between the two. superadmin always bypasses both stages. Reject is unchanged: `config/client_proposal_workflow.php` `reject_roles` plus the client's own Sales Rep, available at either pending stage. Both new permission keys live in the `Proposal` module on `/page_roles_permissions` — grant `proposal.approve.rm` to the "Relationship Manager" role and `proposal.approve.manager` to whichever role should give final approval (e.g. a "Manager" role), same as any other permission. `client_proposals.proposal_request_id` (nullable FK, settable via `store()`/`storeForLead()`) links a commercial proposal back to the `ProposalRequest` it was built from, if any — `approve()`/`attachSigned()` use it to sync that request's own status to `approved`/`signed` (see §2 above). No current frontend caller sets it yet.
- **(c) Signed / Contracts** — `/page_proposal_signed_contracts` → `pages.proposal_signed_contracts`. `ClientProposalController::attachSigned`/`downloadPdf` (upload/download the signed document) + `ClientContractController::createFromProposal` (convert to contract, `/clientProposals/{proposal}/contract`, gated by `contract.create` permission — the one existing permission-table gate in this module, unchanged).
- **(d) My Requests** — `/page_proposal_requests_mine` → `pages.proposal_requests_mine`. The assigned CSR/Relationship Manager's own workspace - lists every `ProposalRequest` assigned to the current user as either `assigned_to` or `relationship_manager_id` (`ProposalRequestAssignmentController::mine()`, exact-match query, no team hierarchy and no superadmin bypass needed since it's inherently self-scoped). An `assigned` row's **Open** button launches the **Request for Proposal wizard** (`components/request-proposal-modal.blade.php` + `resources/js/logic_prospect_request_proposal.js`, `RequestProposalModal`) prefilled with that request's data (`openRequestProposalModal(lead, id)`) - this is the only place the wizard opens from now (the Prospect Info modal's trigger in §2 only mints the row). Any other status's **View** button instead opens a read-only **Proposal Request Summary** modal (`components/proposal-request-summary-modal.blade.php`, `ProposalRequestSummaryModal`, reusing the shared recap) - a `for_approval` request's summary shows a **Cancel Request** action (`POST .../proposalRequests/{id}/cancel`, status → `cancelled`); an `assigned` row also gets a hard **✕ Delete** button (plain delete, no relocation logic needed since Requirements were never attached to it). The wizard always addresses one specific request by id (`/api/crm/prospects/{uuid}/proposalRequests/{proposalRequestId}/...`), never "the latest," since several can exist per prospect at once; it's locked read-only (a persistent amber banner, every mutating control disabled) until `Prospect::isFullyAssigned()` is true, mirroring the server's 409 - see §(a) above for how that gets set. 4 tabs: **Company Details** (read-only Assigned CSR/Relationship Manager display; an editable company-name + location-address snapshot, `proposal_request_company_details`, `POST .../companyDetails`; multiple Authorized Signatories picked from `prospect_contacts`, `proposal_request_signatories`, `POST|DELETE .../signatories[/{id}]`), **Origin & Destination** (a curated list of this prospect's own addresses - live FK references to `prospect_locations`, not copies - `proposal_request_locations`, `POST|DELETE .../locations[/{id}]`; this is the same list that makes the Prospect Info modal's own Origin & Destination tab appear once non-empty), **Products** (a deliberately richer, independent set of 5 product forms - `App\Http\Controllers\ProposalRequestProductController`, dropdown→table→form same shape as the Requirements tab (§2) but its own parallel schema, `proposal_request_product_*` tables: **Container** (CV/FR/RF unified, `proposal_request_product_containers`, Minimum Temperature only for RF, per-side Dispatch Mode disabled when that side's Service Type is Pier), **Rolling Cargo** and **Loose Cargo** (`proposal_request_product_rolling_cargo`/`_loose_cargo`, own Revenue Ton+unit and Cargo Measurement fields; Rolling Cargo only also gets a repeatable **Top Load Cargo** sub-list), **Trucking** (`proposal_request_product_truckings`), and **Charter** (`proposal_request_product_charters`, with repeatable **Cargo Info** and sequentially-numbered **Ports** sub-lists, last port labeled "FINAL PORT"). Container/Rolling/Loose/Trucking rows can each carry a repeatable, polymorphic **Ancillary Services** sub-list (`proposal_request_product_ancillary_services`, one shared modal/endpoint reused across all four). Every sub-list (Ancillary/Top Load/Charter Cargo Info/Charter Ports) is save-row-first: added afterward via that row's own count+"View" button, never built inline before the parent row's first save. `POST|DELETE .../products/{containers,rollingCargo,looseCargo,truckings,charters}[/{id}]` plus nested `.../charters/{id}/{cargo,ports}[/{id}]`, `.../rollingCargo/{id}/topLoad[/{id}]`, and polymorphic `.../ancillaryServices[/{id}]`), and **Proposal Request Confirmation** (read-only recap of the above + a "Confirm & Submit Request" button, `POST .../submit`, only legal from `assigned`, requires Company Details + ≥1 signatory + ≥1 Origin & Destination location; moves `status` from `assigned` to `for_approval` and sets `submitted_at`/`submitted_by`). From there the request's status is synced from its *linked* `ClientProposal` (`client_proposals.proposal_request_id`, set at creation time, nullable - see §(b) above): `approved` once the Manager approves it, `signed` once the signed copy is uploaded - see `ClientProposalController::approve()`/`attachSigned()`. `Prospect::stageCompletionFlags()` gate 3 ("Requirements complete") checks across **all** of a prospect's proposal requests, not just the one currently being worked on, so a since-submitted request's requirements still count. **Gating:** none via `permission:`/`nav.access:` - deliberately self-scoped by the `mine()` query itself, matching the dominant pattern in this app (e.g. `/page_crm`) rather than the rare "only admin/a permission-holder" case `nav.access:` is for; don't mistake the absence of a gate here for an oversight.

The public, signed-URL "client uploads their own signed copy" flow (`ProposalSigningController`, `routes/proposal_signing.php`, `pages_public/proposal_sign.blade.php`, the `approve()`-triggered signature-request email) has been **removed** as part of this restructuring — signing is now handled entirely from the Signed/Contracts page above by an authenticated internal user via `attachSigned`. `ClientProposal::canBeSignedBy`, `signed_document_path`/`signed_at`/`signature_requested_at` columns, and `attachSigned()` itself are unchanged.

## 4. Clients (Client Masters)
- **Route:** `page_clientMasters` → `pages.clientMasters`; form at `/page_clientMasterForm`
- **Controller:** `ClientMasterController`
- **Submodules:** 4-stage client onboarding (`stage1`-`stage4`), delete, client's own proposals/contracts sub-lists.
- **Gating:** none.

## 5. Contracts (Client Contracts)
- **Route:** `/page_contracts` → `pages.contracts`
- **Controller:** `ClientContractController`, `ContractController` (a second, `nav.access`-free `contracts`/`proposals` prefix in `api_maintenance.php` — verify which one the live UI actually calls before adding permission gates, to avoid gating the wrong endpoint)
- **Submodules:**
  - Approve contract (`/clientContracts/{contract}/approve`)
  - Terminate contract (`/clientContracts/{contract}/terminate`)
  - PDF generation
- **Gating:** `contract.terminate` permission gates termination. Approval has no permission gate today.

## 6. Booking / Bookings
- **Route:** `page_booking` and `/page_booking` (both live, same page) → `pages.booking`; wizard at `/page_bookingForm`
- **Controller:** `BookingController`, `BookingDispatchController`, `BookingEirController`, `BookingGateScanController`, `BookingVoyageController`
- **Submodules (all already permission-gated — this module is the reference example for how gating should look elsewhere):**
  - Create/edit booking — `booking.create`
  - Confirm booking — `booking.confirm`
  - Cancel booking — `booking.cancel`
  - Advance status (in-transit / delivered / completed) — `booking.advance-status`
  - Generate dispatch document (ATW/CAN) — `booking.generate-dispatch-document`
  - Assign ConVan/Proforma BL/Waybill/Seal — `booking.assign-cv`
  - Gate scan (Pier Check-In) — `booking.gate-scan`
  - Issue EIR Out/In — `booking.issue-eir`
  - Assign/shut out vessel voyage — `booking.assign-voyage`
  - Generate loadlist / manifest — `booking.generate-loadlist`
  - Quote, BOL view — open (no gate; read/estimate-only)

## 7. Cargo Build-Up
- **Route:** `/page_cargo_build_up` → `pages.cargoBuildUp`
- **Controller:** `CargoBuildUpController`
- **Submodules:** view bookings ready for build-up. Read-only endpoints today.
- **Gating:** none.

## 8. Container Assignment
- **Route:** `/page_container_assignment` → `pages.containerAssignment`
- **Controller:** `ContainerAssignmentController`
- **Submodules:**
  - Auto-assign remaining slots for a booking
  - Assign one slot manually: **scan the container's QR (encodes `container_no`) or type the ID** — both resolve to `container_no`, POSTed to the same `assign` endpoint (which accepts `container_no` or the legacy `container_asset_id`). Uses the same jsQR camera-scan pattern as Pier Check-In (`resources/js/containerAssignment.js`).
  - Unassign a slot
  - Shared assignment logic (Draft-only check, release-before-reassign, container-type match) lives in `ContainerReservationService::assignToUnit()`, used by both this controller and the device endpoint below — don't duplicate it a third time if another entry point is added later.
- **Gating:** none on the browser endpoints (open to all roles, same as Cargo Build-Up).

### 8a. Device Integrations (dormant - not called by anything yet)
- **Route:** `POST /api/device/v1/container-assignments`, registered in `routes/api.php` **outside** the session-based `auth` group.
- **Controller:** `DeviceContainerAssignmentController`
- **Purpose:** lets an external QR-scanner device assign a container directly (`{gate_pass_code, container_no}`) without a browser session, for when real scanner hardware is procured. Prep work only - see the user's original ask.
- **Auth:** Sanctum personal-access token, not a user role. Gated by `middleware(['auth:sanctum', 'abilities:container.assign'])`. Tokens are issued to one shared, roleless "Device Integrations" account via `php artisan device:make-token "<label>"` - never run against a real login. `config/auth.php` gained a `sanctum` guard and `app/Http/Kernel.php` gained the `abilities`/`ability` middleware aliases to support this (both previously unused despite Sanctum being installed).
- **CSRF:** exempted via `VerifyCsrfToken::$except = ['api/device/*']` - token-authenticated requests carry no session cookie, so there's no CSRF token to check.
- **Note:** `container.assign` here is a **Sanctum token ability**, unrelated to the `permissions` table's role-based `container.*` keys (there are none yet) - don't confuse the two systems when extending either one.

## 9. Pier Check-In (Gate Scan)
- **Route:** `/page_pier_checkin` → `pages.pierCheckin`
- **Controller:** `BookingGateScanController`
- **Submodules:** scan container in/out at the gate.
- **Gating:** `booking.gate-scan` permission (group middleware on the whole `gate-scan` prefix).

## 10. Container Inventory (Container Assets)
- **Route:** `/page_container_inventory` (Settings submenu) → `pages.containerInventory`
- **Controller:** `ContainerAssetController`
- **Submodules:** register asset, mark under-repair/available/damaged/out-of-service, relocate, reserve/release (reserve/release are open/no gate — used internally by the booking flow); print a QR label for one container or bulk-print labels for whatever the current filter is showing (client-side only, via `resources/js/containerAssetQr.js` - no new backend route, the QR just encodes the existing `container_no`).
- **Gating:** `nav.access:/page_container_inventory` on all mutations. QR printing is unguarded read-only client-side rendering, not a mutation.

## 11. Vessel Management
- **Route:** `/page_vessel_management` → `pages.vesselManagement`
- **Controller:** `VesselController`
- **Submodules:** vessel CRUD, status change, maintenance records.
- **Gating:** `nav.access:/page_vessel_management` on all mutations.

## 12. Voyage Schedule
- **Route:** `/page_voyage_schedule` → `pages.voyageSchedule`
- **Controller:** `VesselVoyageController`
- **Submodules:** voyage CRUD, manifest/loadlist generation, containers-per-voyage lookup.
- **Gating:** `nav.access:/page_voyage_schedule` on CRUD mutations; `booking.generate-loadlist` permission specifically on manifest/loadlist generation (double-covered by both mechanisms).

## 13. Users
- **Route:** `/page_users` → `pages.settings.users`
- **Controller:** `UserController`
- **Submodules:** create user, edit (`save_info`), deactivate (with self-lockout + last-superadmin guards), reactivate, list/search/filter, role assignment.
- **Gating:** none of the core user CRUD is permission-gated today (only `auth`). The Roles & Permissions sub-feature reached from this page (below) is separately gated.

### 13a. Roles & Permissions (reached via a button on the Users page, no nav entry)
- **Route:** `/page_roles_permissions` → `pages.settings.roles_permissions`
- **Controller:** `RolesController`
- **Submodules:** create role, rename role, delete role (blocked for system roles or roles with assigned users), toggle permissions per role.
- **Gating:** `roles.manage` permission on create/update/delete. Read (`GET /api/roles`, `GET /api/permissions`) is open to any authenticated user (needed to populate role-select dropdowns elsewhere, e.g. the Users page).

## 14. Team Management
- **Route:** `/page_team_management` (Settings submenu) → `pages.settings.team_management`
- **Controller:** `TeamController`
- **Submodules:** team CRUD (with cycle-prevention on re-parenting), member add/remove, leader grant/revoke.
- **Gating:** `nav.access:/page_team_management` on all mutations.

## 15. Settings

### 15a. App Settings (`/page_maintenance` — the large, mostly-untouched rate/reference-data hub)
- **Controller(s):** `PortController`, `LocationController`, `ChargeTypeController`, `DeliveryTypeController`, `ServiceableAreaController`, `LaneController`, `SpecialChargeController`, `CargoYardController`, `LaneTariffRateController`, `PortChargeController`, `GeneralChargeController`, `HandlingFeeController`, `TruckingTariffController`, `VatRateController`, `ContainerController` (container catalog/variants, distinct from Container Inventory's physical assets)
- **Submodules:** each of the above is independent CRUD over a reference-data table (ports, trucking lanes, tariff rates, VAT rates, container catalog, etc.) used throughout booking/proposal rate resolution.
- **Gating:** `nav.access:/page_maintenance` on every mutation across all of the above.

### 15b. Developer Option
- **Mailer** — `/page_mailer`, `MailerController`. Submodules: save SMTP config, send test mail. Gating: none beyond `auth` (role-restricted only via nav visibility: roles `4,1`).
- **Menus** — `/page_menus`, `MenusController`. Submodules: create/edit/delete nav menu entries, reorder, set allowed roles per menu, group top-level menus under a free-text `category` label (only meaningful on `parent_menu = 0` rows — a child ignores/never has one). Gating: none beyond `auth` + nav visibility (roles `4,1`).
- **Theme** — `/page_theme`, `AppThemeController`. Submodules: set main/accent/secondary/danger color, dark-mode preference (currently inert, see `CLAUDE.md`). Gating: none beyond `auth` + nav visibility.
- **Application Information** — `/page_app_information`, `AppInformationController`. Submodules: set app name/logo/favicon. Gating: none beyond `auth` + nav visibility.
- **Notification Test** — `/page_notification_test`, `NotificationController::testSend`. Gating: `can:isSuperAdmin` Gate (a third, separate authorization mechanism from `permission:`/`nav.access:` — see "Known gaps").

---

## Pages that exist but are not part of the live nav (flag before assuming coverage)

These have routes/controllers but no `NavMenuSeeder` entry, so they're reachable only by direct URL, an in-page button, or not at all. Don't assume `nav.access:` can gate them (that middleware 403s if no matching `nav_menus` row exists) unless a row is added first.

- `/page_usermanagement` → `pages.settings.usersmanagement` — superseded by `/page_users`; kept only as an internal reference file per team decision, not to be wired back into nav.
- `/page_roles_permissions` — intentionally nav-less; reached via the "Roles & Permissions" button on the Users page.
- `/page_reports`, `/page_help`, `/page_profile`, `/profile`, `/settings` — stubs or unfinished pages.
- `/page_bookingForm`, `/page_clientMasterForm` — sub-forms opened from within their parent module, not standalone nav destinations (expected, not a gap). (`/page_crmLeadForm` was retired — CRM intake is now the Prospect modal on `/page_crm`.)
- `routes/api_booking.php`, `routes/api_contracts.php`, `routes/api_master.php`, `routes/pageApi.php` — entire files are orphaned/unreachable (never `require`d). Do not add permissions scoped to these; they're dead code per `CLAUDE.md`.
- `PageController` methods with no route at all: `page_Forms`, `page_featuredHome`, `page_documents`, `page_approvals`, `page_reports_documents`, `page_reports_users`, `page_finance_tracker`.
- `app/Http/Controllers/ListingController.php`, `ActivityController.php`, `app/Http/Controllers/Api/MenuController.php` — no route references found anywhere; verify before relying on them.

## Known gaps (existing authorization is NOT unified under one system)

Four separate, uncoordinated authorization mechanisms currently coexist:

1. **`permission:` middleware** (`App\Support\PermissionHelper`, the `permissions`/`role_permission` tables) — the modern one, covers Booking actions, Contract create/terminate, `roles.manage`, and (model-level, not route middleware) the two-step Proposal approval chain (`proposal.approve.rm` / `proposal.approve.manager` — see §3(b)).
2. **`nav.access:` middleware** (`EnsureNavMenuAccess`, checks a `nav_menus` row's `allowed_roles`) — covers most Settings-area mutations (App Settings, Vessel Management, Voyage Schedule, Container Inventory, Team Management, Users' role-config sub-feature).
3. **Ad hoc role/team checks** — `TeamService` hierarchy for CRM leads, `config/client_proposal_workflow.php` reject_roles, `can:isSuperAdmin` Gate for a couple of superadmin-only actions, and direct `role_name` string comparisons via `RoleHelper` scattered through controllers.
4. **Sanctum token abilities** (`auth:sanctum` + `abilities:`) — brand new, currently only guards the dormant device-integration endpoint (§8a). Not role-based at all: a token either has the ability or it doesn't, independent of any user/role. Don't conflate a token ability name (e.g. `container.assign`) with a same-looking `permissions` table key — they're checked by completely different code paths.

When expanding the `permissions` table to cover a module that currently relies on (2) or (3), decide deliberately whether to *replace* that mechanism or layer a permission check *alongside* it — don't assume a coarse permission key alone preserves existing per-deal restrictions. Proposal approval's RM stage is the pattern to copy when a permission needs to be scoped to "this specific assigned individual," not just "does this role have a flag": `canBeApprovedByRm()` checks the permission *and* that the caller is this deal's own `relationship_manager_id`, whereas the Manager stage (`canBeApprovedByManager()`) is a flat, non-scoped permission check.

---

## Keeping this in sync with Roles & Permissions

1. Adding a new module/page → add a section here (route, controller, submodules) before or alongside the implementation PR.
2. Adding a new mutating action to an existing module → add it to that module's submodule list here.
3. After either of the above, decide whether the new action needs a permission key:
   - If yes: add a row to the `permissions` table (via a migration/seeder, with `key`/`label`/`module`), wire `->middleware('permission:the.key')` on its route, and grant it to the appropriate existing roles (typically at least `superadmin`).
   - Then update this file's entry for that module to note the new gating, so the "Gating" line here never drifts from what's actually enforced in `routes/`.
4. The Roles & Permissions page (`/page_roles_permissions`) reads `GET /api/permissions` live from the `permissions` table — it does not need code changes when permissions are added, only the seed/migration step above.
