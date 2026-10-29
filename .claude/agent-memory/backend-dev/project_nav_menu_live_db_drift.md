---
name: project-nav-menu-live-db-drift
description: Live nav_menus rows can drift from database/seeders/NavMenuSeeder.php's hardcoded allowed_roles — always read the live row before assuming the seeder reflects reality.
metadata:
  type: project
---

Found 2026-10-20 while consolidating the Requests/Approvals/Signed-Contracts proposal pages into one page (backend brief: broaden `ProposalRequestAssignmentController::index()`, delete the "Approvals"/"Signed / Contracts" `nav_menus` rows, broaden "Request"'s `allowed_roles`).

`NavMenuSeeder.php`'s `'Request'` entry had `allowed_roles => ['1', '2']` in the seeder source, but the **live** `nav_menus` row for `'Request'` (id 24) already had `allowed_roles => ["1","2","3","4","5","6","7","8"]` (all roles) before this task touched anything — same for the `'Approvals'`/`'Signed / Contracts'` rows (id 25/26), also already `["1",...,"8"]`. The live DB is edited directly through the Menus admin page (`/page_menus`) and visibly does not stay in sync with the seeder file.

**Why:** Someone (another agent or a human) had already broadened those nav rows live, independently of the seeder. The seeder file is not a reliable source of "what roles currently see this menu item" — only a baseline for fresh installs/resets.

**How to apply:** Before editing or reporting on any `nav_menus` row's `allowed_roles`/existence, query the live table (`App\Models\NavMenu::where(...)->get()`) rather than trusting what's in `NavMenuSeeder.php`. When a brief asks for a specific seeder change *and* a live-DB tinker command, run both and verify the live result independently — don't assume the "before" state matches the seeder's old value.

**Update 2026-10-20 (QA fix):** The consolidation task above narrowed `'Request'`'s seeder/live `allowed_roles` to `['2','5','4','6','1','3']` (dropping roles 7 and 8), and a later `db:seed` run applied that narrow value live, 403'ing CSR/RM users on `/page_proposal_requests`. Re-broadened both seeder and live row to all 8 roles (`['1'..'8']`), matching the sibling `'My Requests'` row. Confirmed via `EnsureNavMenuAccess`-equivalent check that role_id 7 and 8 now pass. Lesson holds either direction: running the seeder can also *overwrite* a live row that had already been hand-broadened via `/page_menus` — don't assume seeding is a no-op just because the seeder "only adds."
