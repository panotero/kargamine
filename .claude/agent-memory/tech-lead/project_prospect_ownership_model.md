---
name: prospect-ownership-model
description: prospects.owner_id edit-rights model, team-wide visibility, team-leader reassignment; and the TWO distinct prospect authorization tiers
metadata:
  type: project
---

Prospects now have an `owner_id` (nullable FK users, backfilled `= created_by` on migrate) separate from `created_by` (permanent/historical) and `assigned_to` (Management's staffed CSR for the RFP flow). `owner_id` starts `= auth()->id()` at creation and only changes via a deliberate reassignment.

**Why:** csr1 created a lead but it was invisible everywhere — `ProspectController::index()` filtered strictly on `assigned_to`, which starts null until Management staffs the deal. Also discovered the core prospect-edit endpoints had ZERO authorization (any logged-in user could edit any prospect). This change fixed visibility AND closed that gap.

**How to apply:**
- Visibility: `index()` uses `TeamService::accessibleTeamUserIds()` (NEW method = all users in own team + descendant teams; unlike `accessibleUserIds()` which is self-only for a non-leader) and matches `created_by OR owner_id OR assigned_to`. Do NOT widen `accessibleUserIds()`/`accessibleTeamIds()` — 6 other list views depend on their contract.
- Edit rights: `Prospect::canBeEditedBy()` = owner, or an in-reach team leader (owner's team within leader's `accessibleTeamIds`), or superadmin. `canBeReassignedBy()` = leader/superadmin only (a plain owner cannot reassign themselves). Gated via `ProspectController::authorizeProspectEdit()` on update/saveStage1(update-branch)/saveStage2/storeContainer/saveContacts/getOrGenerateCustomerCode/destroy.
- Reassign: `POST /api/crm/prospects/{uuid}/reassign-owner` `{owner_id}` → leader-only, target must be within `accessibleUserIds` (strict subtree, NOT accessibleTeamUserIds). `show()` exposes `can_edit`/`can_reassign` + eager-loads `owner:id,name,team_id`.

**TWO distinct prospect authorization tiers — do not conflate:**
1. This `owner_id` edit tier (CRM intake editing) — `canBeEditedBy`/`canBeReassignedBy`.
2. `AuthorizesProposalRequestAccess::authorizeProspectOwnership()` — the LATER RFP/proposal-request lifecycle tier (exact `assigned_to`/`relationship_manager_id` match, set by Management's `assignOwners()`). Untouched by the ownership work and orthogonal. Don't add `authorizeProspectEdit` to any `proposalRequest*` method.

Auth-failure bodies: use `throw new HttpResponseException(response()->json(['success'=>false,...], 4xx))`, NOT `abort()` — `abort()` emits a bare `{message}` that violates the `{success}` envelope the frontend's `apiCall` relies on. The sibling tier documents this choice in its own class docblock.

Pre-existing bug noticed (NOT fixed here, out of scope): `ProspectController::destroy($id)` does `Prospect::findOrFail($id)` by integer PK but its route is `DELETE /prospects/{uuid}` — a uuid won't resolve. Likely a dead/broken path; flagged for a future task.

Related: [[project_prospect_activity_and_rfp_sections]], [[project_proposal_request_od_design]]
