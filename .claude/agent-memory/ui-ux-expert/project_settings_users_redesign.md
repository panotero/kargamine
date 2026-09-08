---
name: project-settings-users-redesign
description: Settings/Users + Team Management redesign review — the LIVE users page is a fake mockup while the real backend-wired user admin sits orphaned
metadata:
  type: project
---

Settings-area redesign, Batch B: `pages/settings/users.blade.php` (live via `page_Users`) and `pages/settings/team_management.blade.php` (live via `page_team_management`).

Non-obvious finding worth keeping: the **wrong users file is wired up**. `PageController::page_Users` renders `pages/settings/users.blade.php`, which is a self-contained blue-themed MOCKUP running entirely on hardcoded JS arrays (`USERS`, `ROLES`, `LOGIN_ACTIVITY`, `ACCOUNTS`) with `// TODO: axios.post` stubs — no backend, no persistence, invents a data model (Department free-list, "Assigned Accounts" shipper/consignee, a client-side permission matrix) that does not exist in the app.

Meanwhile the *real* backend exists and is unused: `UserController` resource + `/api/users` group (`index`, `counts`, `byRole`, `show`, `store`, `save_info`, `deactivate`, `reactivate`). Real user model = `name`, `email`, `password`, `role_id` → `SettingRole`, `department` → `UserDepartment`, `must_change_password`, active/inactive via deactivate/reactivate. The orphaned `pages/settings/usersmanagement.blade.php` (dead, not routed) is the one actually wired to that API with `apiCall` in the correct zinc design language.

**Why:** the redesign must be a rebuild against the real `UserController` API in zinc, not a restyle of the mock. Team/leader assignment is NOT on the users page at all — it lives in Team Management's Members modal (`is_team_leader` per-membership checkbox), and its cascade effect (per `TeamService`) is unexplained in both places.

**How to apply:** when this reaches implementation, base the users screen on the real API surface above; treat the mock's roles/permissions/online/login-activity features as fabricated (need backing or cut). Team Management page itself is real and correctly styled but has hand-rolled modals, no button loading states (uses `fetchWithRetry` not `apiCall`), non-searchable selects, and whole-row-click delete. See [[project-container-inventory-redesign]] for the same button-loading/confirmation bug class seen across this session.
