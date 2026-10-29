---
name: nav-access-role-baseline
description: Gating a page via nav.access with the common NavMenuSeeder allowed_roles baseline silently 403s Relationship Managers and CSRs
metadata:
  type: feedback
---

When a route is gated by `nav.access:/page_x` (`EnsureNavMenuAccess` checks `user->role_id` ∈ that nav_menus row's `allowed_roles`), DO NOT reuse the generic `['2','5','4','6','1','3']` baseline that most NavMenuSeeder rows copy-paste — it is only roles 1-6 and OMITS role `7` (Relationship Manager) and role `8` (Customer Service Representative). The live `setting_role` table has 8 roles.

**Why:** In the Request/Approvals/Signed-Contracts consolidation, I briefed the combined page's `allowed_roles` as that 6-role "superset," which silently 403'd every CSR/RM — the exact audience the page was for (the page shell rendered but its first `/api/proposalRequests` call died). QA caught it by running the real middleware for role_id 7 and 8. Fix was to broaden to all 8 (`['1'..'8']`), matching the sibling My Requests row.

**How to apply:** For any CRM/proposal/workflow page meant for front-line sales staff, the `allowed_roles` MUST include `7` and `8` (or just use all 8). Better still, when the controller already does its own team-scoping (as `index()` now does, mirroring `mine()`), prefer dropping `nav.access` entirely over maintaining a role list — that is this app's dominant pattern for self-scoped lists. See [[project_prospect_ownership_model]] for the team-scoping helpers. Verify role gates with the actual middleware/role_id comparison, never by eyeballing the array.
