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

## 2. CRM (Leads)
- **Route:** `/page_crm` → `pages.crm`; lead detail form `/page_crmLeadForm`
- **Controller:** `CrmLeadController`, `CrmStatusController`, `CrmActivityController`, `CrmNoteController`
- **Submodules:**
  - Create lead (stage 1 / stage 2), edit, delete
  - Lead containers (`/leads/{uuid}/containers`)
  - Lead → Proposal creation (`/leads/{uuid}/proposals`)
  - Notes, activities, DG document upload
  - Team-hierarchy visibility (`TeamService::accessibleTeamIds`) — a rep sees only their own leads, a team leader sees their subtree's leads
- **Gating:** none via `permission:`/`nav.access:` today — visibility is scoped by `TeamService` team hierarchy instead, not a permission key.

## 3. Proposals (Client Proposals)
- **Route:** `page_proposals` → `pages.proposals`
- **Controller:** `ClientProposalController`
- **Submodules:**
  - Create/edit proposal + rates (`/clientProposals/{proposal}/rates`)
  - Approve / Disapprove (`ClientProposal::canBeApprovedBy` — team-leader-of-assigned-rep, gated by team hierarchy, not a permission key)
  - Reject (`config/client_proposal_workflow.php` `reject_roles`, plus the client's own Sales Rep)
  - Sign / attach signed document (`ClientProposal::canBeSignedBy` — any member of the approving team)
  - Convert to contract (`/clientProposals/{proposal}/contract`)
  - PDF generation (`/clientProposals/{proposal}/pdf`)
- **Gating:** `contract.create` permission gates the proposal→contract conversion. Approve/disapprove/reject/sign are gated by the team-hierarchy + config-role logic above, **not** by a `permissions` table row — a real gap if this system is meant to fully replace ad hoc role checks (see "Known gaps" below).

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
- **Menus** — `/page_menus`, `MenusController`. Submodules: create/edit/delete nav menu entries, reorder, set allowed roles per menu. Gating: none beyond `auth` + nav visibility (roles `4,1`).
- **Theme** — `/page_theme`, `AppThemeController`. Submodules: set main/accent/secondary/danger color, dark-mode preference (currently inert, see `CLAUDE.md`). Gating: none beyond `auth` + nav visibility.
- **Application Information** — `/page_app_information`, `AppInformationController`. Submodules: set app name/logo/favicon. Gating: none beyond `auth` + nav visibility.
- **Notification Test** — `/page_notification_test`, `NotificationController::testSend`. Gating: `can:isSuperAdmin` Gate (a third, separate authorization mechanism from `permission:`/`nav.access:` — see "Known gaps").

---

## Pages that exist but are not part of the live nav (flag before assuming coverage)

These have routes/controllers but no `NavMenuSeeder` entry, so they're reachable only by direct URL, an in-page button, or not at all. Don't assume `nav.access:` can gate them (that middleware 403s if no matching `nav_menus` row exists) unless a row is added first.

- `/page_usermanagement` → `pages.settings.usersmanagement` — superseded by `/page_users`; kept only as an internal reference file per team decision, not to be wired back into nav.
- `/page_roles_permissions` — intentionally nav-less; reached via the "Roles & Permissions" button on the Users page.
- `/page_reports`, `/page_help`, `/page_profile`, `/profile`, `/settings` — stubs or unfinished pages.
- `/page_bookingForm`, `/page_clientMasterForm`, `/page_crmLeadForm` — sub-forms opened from within their parent module, not standalone nav destinations (expected, not a gap).
- `routes/api_booking.php`, `routes/api_contracts.php`, `routes/api_master.php`, `routes/pageApi.php` — entire files are orphaned/unreachable (never `require`d). Do not add permissions scoped to these; they're dead code per `CLAUDE.md`.
- `PageController` methods with no route at all: `page_Forms`, `page_featuredHome`, `page_documents`, `page_approvals`, `page_reports_documents`, `page_reports_users`, `page_finance_tracker`.
- `app/Http/Controllers/ListingController.php`, `ActivityController.php`, `app/Http/Controllers/Api/MenuController.php` — no route references found anywhere; verify before relying on them.

## Known gaps (existing authorization is NOT unified under one system)

Four separate, uncoordinated authorization mechanisms currently coexist:

1. **`permission:` middleware** (`App\Support\PermissionHelper`, the `permissions`/`role_permission` tables) — the modern one, only covers Booking actions, Contract create/terminate, and `roles.manage` today.
2. **`nav.access:` middleware** (`EnsureNavMenuAccess`, checks a `nav_menus` row's `allowed_roles`) — covers most Settings-area mutations (App Settings, Vessel Management, Voyage Schedule, Container Inventory, Team Management, Users' role-config sub-feature).
3. **Ad hoc role/team checks** — `TeamService` hierarchy for CRM leads and Proposal approval, `config/client_proposal_workflow.php` reject_roles, `can:isSuperAdmin` Gate for a couple of superadmin-only actions, and direct `role_name` string comparisons via `RoleHelper` scattered through controllers.
4. **Sanctum token abilities** (`auth:sanctum` + `abilities:`) — brand new, currently only guards the dormant device-integration endpoint (§8a). Not role-based at all: a token either has the ability or it doesn't, independent of any user/role. Don't conflate a token ability name (e.g. `container.assign`) with a same-looking `permissions` table key — they're checked by completely different code paths.

When expanding the `permissions` table to cover a module that currently relies on (2) or (3), decide deliberately whether to *replace* that mechanism or layer a permission check *alongside* it — don't assume moving to `permission:` middleware alone preserves existing behavior (e.g. Proposal approval's team-hierarchy restriction encodes "which specific leader," not just "does this role have a flag," and a coarse permission key can't express that on its own).

---

## Keeping this in sync with Roles & Permissions

1. Adding a new module/page → add a section here (route, controller, submodules) before or alongside the implementation PR.
2. Adding a new mutating action to an existing module → add it to that module's submodule list here.
3. After either of the above, decide whether the new action needs a permission key:
   - If yes: add a row to the `permissions` table (via a migration/seeder, with `key`/`label`/`module`), wire `->middleware('permission:the.key')` on its route, and grant it to the appropriate existing roles (typically at least `superadmin`).
   - Then update this file's entry for that module to note the new gating, so the "Gating" line here never drifts from what's actually enforced in `routes/`.
4. The Roles & Permissions page (`/page_roles_permissions`) reads `GET /api/permissions` live from the `permissions` table — it does not need code changes when permissions are added, only the seed/migration step above.
