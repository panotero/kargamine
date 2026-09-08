---
name: project-booking-redesign
description: Booking list page + booking create/edit form redesign review — user's #1 priority is comprehension/simplicity, not visual polish
metadata:
  type: project
---

Booking module redesign (review-only pass, synthesized into an HTML mockup before code). Covers `resources/views/pages/booking.blade.php` (list + giant view/manage modal) and `resources/views/pages/bookingForm.blade.php` (create/edit).

**Why:** User's explicit words — "in the booking i really want this to be very very userfriendly and easy to understand." The current booking experience is the densest, most jargon-heavy flow in the app (ATW/CAN, EIR, CV Assignment, Trip Type, delivery mode Door/Pier). Comprehension outranks visual consistency here.

**How to apply / key recommendations from first review:**
- bookingForm is a wall of fields → recommend guided multi-step wizard (VISUALS.md) + exclusive-expand for repeatable Cargo Line cards and container rows (that pattern was literally established for booking-requirement cards in the CRM Lead modal — reuse it, don't duplicate a denser version).
- Cargo Line route block should use VISUALS.md route/flight-card pattern (origin→dest symmetric columns + arrow).
- booking.blade.php's `viewBookingModal` stacks 4 backend sub-workflows (dispatch/CV/EIR/status) in one scroll → recommend rail+tabs (Lead Info modal pattern) with a "what stage is this / what's next" summary strip.
- Register a booking-status hue mapping in VISUALS.md (Draft=zinc, Confirmed=blue, In Transit=indigo, Delivered=teal, Completed=emerald, Cancelled=red); 7-card status grid should become a linear lifecycle stepper (Cancelled as off-ramp).

**Real bugs found (not opinion):**
- "Save as Draft works any time" is FALSE — `saveDraftBtn` hits the same `store`/`update` endpoint whose `validatePayload()` requires consignee x4 + delivery_date + delivery_date_notes + first/last delivery date per line. Draft save fails on a partial form. Top contradiction.
- Status-transition action buttons (`vbConfirmBtn`, `vbMarkInTransitBtn`, `vbMarkDeliveredBtn`, `vbMarkCompletedBtn`, `vbCancelConfirmBtn`) call `performAction()` with NO `button:` passed to apiCall → no loading/disable → double-submit. (Missing-button-loading bug class, same as prior pages.)
- Filtered-empty message bug class present: `renderTable()` omits `emptyMessage`, so both true-empty and filtered-to-zero show default "No records yet."
- Inconsistent dark-mode input styling: client-search input uses `dark:bg-zinc-900 dark:text-zinc-100`; nearly every other form/modal input uses only `dark:text-zinc-900` (black text, no dark bg).

See [[project-crm-leads-list-redesign]], [[project-client-masters-redesign]] for the same-session redesign conventions.
