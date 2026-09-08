---
name: settings-area-redesign
description: Settings area redesign (batch 1 = settings.blade.php + menus.blade.php); key finding that App Settings CRUD endpoints are unregistered/dead
metadata:
  type: project
---

Settings area is being redesigned in 3 parallel review batches (App Settings, Users, Theme, Mailer, App Information, Notification Test, Team Management, Menus). Batch 1 = `pages/settings/settings.blade.php` ("Office & User Configuration") + `pages/settings/menus.blade.php`. Review-only pass; findings get synthesized into an approved HTML mockup before any code.

**Why:** User asked to modernize the whole Settings area; settings.blade.php is the single most outdated page in the app (still gray/blue palette, hand-rolled modals, raw fetch, no delete confirmation).

**How to apply:** When reviewing/implementing here, the biggest issue is not styling — it's that `settings.blade.php` calls four API endpoints that DO NOT EXIST in the route table: `/api/offices`, `/api/userconfigs`, `/api/documenttypes`, `/api/labeltypes`. Confirmed via `php artisan route:list` (271 api routes, 0 matches). Only `UserConfigController` exists as a controller; no Office/DocumentType/LabelType controllers or models wired, no routes registered. So the page is functionally non-operational today (all four tables load empty, nothing saves/deletes). Any redesign implementation MUST include the backend (models + controllers + routes) or the mockup is decorating a dead page. Verify current route state before assuming these exist — a later batch may have wired them.

Related: [[crm-leads-list-redesign]] and the other redesign memories share this session's standard fixes (button loading state, empty-state distinction, customConfirm on destructive actions, required-field left-border accent).
