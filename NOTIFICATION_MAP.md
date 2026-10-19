# Notification Map

What happens, mechanically, when something in Kargamine is worth telling
someone about - who gets told, through which channel, and what (if
anything) happens next. This started as a point-in-time audit (as of
2026-10-14) and is now also the running record of which gaps have actually
been closed - keep both in sync as more get wired up.

A companion visual version of this file (flow diagrams + the full table
below, color-coded) is published as a Claude Artifact in the same
conversation this file was written in.

## The mechanism, in one paragraph

There is no Laravel `Notifications` package in use here - the whole system
is custom. `App\Services\TeamNotifier::notify($userIds, $payload)` is the
one helper every controller action calls, and it always does two things
for each recipient: (1) insert a row into the `notifications` table (the
bell icon, via `NotificationService::send()`) and (2) queue a real email
(via `ApplicationMailer::send()` → `SendApplicationMailJob::dispatch()`, a
`ShouldQueue` job that lands on the `jobs` table and sends once a queue
worker picks it up). In-app and email are coupled 1:1 everywhere this
helper is used - there is no code path today where one fires without the
other, except the superadmin-only manual test tool
(`NotificationController::testSend()`), which is in-app only by design.

**So "is there a notification for X" and "is there an email for X" are the
same question**, for every event below except that one test tool.

## The process, generically

> Someone does something → `TeamNotifier::notify()` is called with a list
> of user ids and a payload → every one of those users gets a bell
> notification + a queued email → if that something was itself a decision
> point (an approval stage), the *next* person in the chain now has
> something to act on, and acting on it triggers the next `notify()` call.

Concretely, the richest real example in the app - the Proposal Request /
Client Proposal approval chain - looks like this:

1. **CSR submits a Request for Proposal.** Two independent notifications
   fire: every Admin/Superadmin is told a new request exists and needs
   assignment, and (if the deal already has an assigned Relationship
   Manager) that RM is separately told a proposal is waiting on them.
2. **RM reviews and approves (stage 1).** The proposal moves to
   Manager-level approval. Every user holding the `proposal.approve.manager`
   permission is notified it's now waiting on them.
3. **Manager makes the final call** - approve, disapprove, or reject.
   Whichever it is, the person who originally created the proposal is
   notified of the outcome.

Nobody has to poll or refresh to find out their step is next - the chain
pushes forward on its own, one `TeamNotifier::notify()` call per handoff.

A second, independent, single-hop chain exists for lead intake: when a CSR
creates a new Prospect, their direct Team Leader(s) are notified (so a
leader can see new pipeline without checking the CRM list manually) - this
one doesn't chain any further, it's a single notify-and-done.

## Already wired (7 events)

| # | Event | Triggered by | Recipient(s) |
|---|---|---|---|
| 1 | Prospect/lead created | `ProspectController::saveStage1` (`app/Http/Controllers/ProspectController.php:362`) | Creator's direct team leader(s) |
| 2 | Proposal Request needs Management assignment | `ProspectController::storeProposalRequestNew` (`:850`) | All superadmin/admin users |
| 3 | Proposal Request submitted for review | `ProspectController::submitProposalRequest` (`:1195`, bridges to #4 at `:1182`) | All superadmin/admin users |
| 4 | Proposal awaiting RM approval | `ClientProposalController::notifyProposalPending` (`app/Http/Controllers/ClientProposalController.php:679`) | The deal's assigned Relationship Manager |
| 5 | Proposal forwarded to Manager approval | `ClientProposalController::notifyProposalPendingManager` (`:710`) | Users holding `proposal.approve.manager` |
| 6 | Proposal decided (approved / disapproved / rejected) | `ClientProposalController::notifyProposalDecision` (`:734`) | Proposal's original creator |
| 7 | Prospect assigned a CSR + Relationship Manager | `ProspectController::assignOwners` (`:171-214`) | The newly assigned CSR *and* the newly assigned RM, each told which role they were given |

Event 7 added 2026-10-14 - was a gap, closed with two `TeamNotifier::notify()`
calls (one per role) instead of one call to both ids, so each recipient's
message says which role they were assigned as, and the same person being
both CSR and RM doesn't get double-notified (the RM call is skipped when
`relationship_manager_id === assigned_to`).

Plus the Laravel Breeze defaults, unrelated to the above but also real
email: password reset request, password reset confirmation, email
verification.

## Gaps - state-changing or handoff actions with no notification today

Grouped by module; each row is a real controller action that changes who
owns something or needs someone's attention, with nothing telling that
person it happened.

### CRM / Proposal Request lifecycle

| Event | Where |
|---|---|
| Proposal Request cancelled | `ProspectController::cancelProposalRequest` (`:1219-1251`) |
| Proposal Request deleted (pre-submission) | `ProspectController::destroyProposalRequest` (`:878-900`) |

### ClientProposal (inconsistent with its own siblings)

| Event | Where |
|---|---|
| Proposal cancelled | `ClientProposalController::cancel` (`:437-469`) - unlike approve/disapprove/reject, which all notify the creator, cancel doesn't |
| Signed document uploaded / proposal accepted | `ClientProposalController::attachSigned` (`:474-561`) |

### ClientContract

| Event | Where |
|---|---|
| Contract created from an approved proposal | `ClientContractController::createFromProposal` (`:227-276`) |
| Contract approved | `ClientContractController::approve` (`:320-342`) |
| Contract terminated | `ClientContractController::terminate` (`:344-367`) |

### Booking / container / dispatch / EIR - entire module, zero wiring

| Event | Where |
|---|---|
| Booking created | `BookingController::store` (`:141-186`) |
| Booking confirmed | `BookingController::confirm` (`:290-394`) |
| Booking cancelled | `BookingController::cancel` (`:421-458`) |
| Booking status advanced (In Transit / Delivered / Completed) | `BookingController` `transitionStatus` (`:460-501`) |
| Dispatch document generated | `BookingDispatchController::generate` (`:18-69`) |
| ConVan / BL / Waybill / Seal assigned | `BookingDispatchController::updateCvAssignment` (`:71-82`) |
| EIR Out issued | `BookingEirController::issueOut` (`:37-77`) |
| EIR In issued | `BookingEirController::issueIn` (`:79-112`) |
| Gate scan (Pier Check-In/Out) | `BookingGateScanController::scan` (`:44-91`) |
| Voyage assigned to a container unit | `BookingVoyageController::assign` (`:15-37`) |
| **Container Shut Out** (missed vessel cutoff) | `BookingVoyageController::shutOut` (`:39-51`) - the standout candidate here, it's an exception/problem event, not routine progress |

### Team management

| Event | Where |
|---|---|
| Member added to a team | `TeamController::addMember` (`:122-142`) |
| Team-leader status granted/revoked | `TeamController::updateMember` (`:144-163`) |
| Member removed from a team | `TeamController::removeMember` (`:165-171`) |

### User account lifecycle

| Event | Where |
|---|---|
| New user account created (temp password, no welcome email) | `UserController::store` (`:186-210`) |
| User deactivated | `UserController::deactivate` (`:74-119`) |
| User reactivated | `UserController::reactivate` (`:121-144`) |

### Lower priority

| Event | Where |
|---|---|
| Container asset marked Damaged / Under Repair / Out of Service | `ContainerAssetController` (`:146-267`) |
| Container manually assigned/unassigned to a booking slot | `ContainerAssignmentController::assign` / `unassign` (`:132-193`) |

Checked and found to have no real cross-person handoff worth notifying on:
`ProposalRequestAssignmentController` (read-only), `ProposalRequestProductController`
(same-owner data entry), `CrmStatusController`, `CrmNoteController`,
`CrmActivityController`, `RolesController`, `ClientMasterController`.

## Infrastructure notes

- **Queue**: `.env` sets `QUEUE_CONNECTION=database`; `jobs`/`failed_jobs`
  tables exist and are migrated. `SendApplicationMailJob` (`app/Jobs/`)
  implements `ShouldQueue` and is what actually sends - `ApplicationMailer::send()`
  just dispatches it. A second job class, `SendExternalMailJob`, exists for
  non-`User` email recipients but has no call site anywhere - dead code.
- **No queue worker found in this repo.** No `queue:work`/`queue:listen`
  in any startup script. Confirm with whoever manages the server that
  something is actually running `php artisan queue:work` - otherwise every
  queued email above sits in the `jobs` table unsent.
- **SMTP config is DB-driven, not `.env`-driven**, for every email that
  goes through the job: both mail jobs load `MailerSetting::latest()` (the
  `/page_mailer` admin screen) and `Config::set()` the `smtp` mailer from
  it at send time.
- **Likely-broken legacy path**: `RoutingController::routeDocument()`
  (`app/Http/Controllers/RoutingController.php:127,184`) raw-inserts into
  `DB::table('notifications')` with columns (`document_id`, `office_origin`,
  `destination_office`) that no longer exist on the current `notifications`
  table schema. Not touched as part of this audit - flagging since it would
  likely throw a SQL error if that route is ever hit.

## If closing a gap

`TeamNotifier::notify($userIds, $payload)` already does "bell + queued
email" in one call and is the established pattern for all 6 wired events
above - closing any gap in this file means adding one call at the relevant
controller action, matching the shape already used there. No new
infrastructure needed for anything except possibly the Booking module,
which is a larger surface (5 controllers, 10 distinct points) but the same
pattern repeated.
