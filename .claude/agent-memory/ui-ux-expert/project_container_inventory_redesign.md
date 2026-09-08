---
name: project-container-inventory-redesign
description: Container Inventory (containerInventory.blade.php) redesign review — status-shape reasoning, booking-driven read-only states, dropped-reason bug, orphan statuses
metadata:
  type: project
---

Review of `resources/views/pages/containerInventory.blade.php` (physical container fleet registry; controller `ContainerAssetController`, model `ContainerAsset`, service `ContainerReservationService`).

**Why:** Same review-only redesign series as [[project-booking-redesign]] / [[project-client-masters-redesign]] — my report feeds an HTML mockup before any code.

**How to apply / key conclusions from this pass:**
- 6 statuses are NOT a linear progression. Available/Booked/In Transit = booking-driven operational cycle (this page can't set/clear them — set by BookingController via ContainerReservationService). Under Repair/Damaged/Out of Service = condition branches. Recommended grouped chip filters with a divider (like Booking's Cancelled off-ramp), NOT a directional arrow strip.
- Reserve/release endpoints exist on the controller but are wired only to the Booking flow, not this page. Page's own actions: Mark Under Repair, Mark Available, Mark Out of Service, Relocate. Page never explains Booked/In Transit are read-only here — the domain-overlap confusion the Booking review warned about.
- Real bugs found: (1) markUnderRepair/markOutOfService accept a `reason` but the JS sends `payload:{}` — reason silently dropped; contradicts Booking cancel-needs-reason pattern. (2) `condition_notes` never displayed in detail modal. (3) STATUS_DAMAGED(5) has chip+badge but NO action reaches it — orphan/always-empty filter. (4) Out of Service is terminal in UI (markAvailable backend only allows from Under Repair). (5) Long selects (variant/port/pier, filterVariant, relocate) are bare native — must use makeSearchableSelect. (6) No filtered-empty message. (7) `dark:text-zinc-900` on modal inputs with no dark bg = dark-on-dark.
- Good already: real `<button>` filter chips, button-loading discipline present on all actions (`button:` passed), pagination + backend search exist, no segmented-distribution-bar anti-pattern.
- Chips have no counts (unlike Booking/ClientMaster) and there's no header stat strip; index endpoint returns no status counts — backend addition needed for "Available now / Needs attention" stats.
