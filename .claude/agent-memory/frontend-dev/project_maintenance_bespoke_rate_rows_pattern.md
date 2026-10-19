---
name: project-maintenance-bespoke-rate-rows-pattern
description: resources/views/pages/maintenance.blade.php's generic ENTITY_CONFIG slide-over gets special-cased with a bespoke modal whenever an entity needs repeatable child rows (Location->Ports/Serviceable Areas, Container->Sizes/Classes, Special Charges->per-unit rates as of 2026-10-20)
metadata:
  type: project
---

`maintenance.blade.php` has a generic ENTITY_CONFIG-driven slide-over (`#maintenanceFormModal`) for
plain flat-field CRUD, but entities needing a repeatable child-row list get fully special-cased
instead: own `<x-side-modal>`, own `open<Entity>Form(id)`, own submit listener, own row builder
reusing the shared `idRowHtml()`/`collectIdRows()` (plain text+hidden-id rows) or a custom row HTML
function when a row needs more than one field (select + currency input, etc.) — see
`specialChargeRateRowHtml()`/`addSpecialChargeRateRow()`. The wiring points to touch for a new one
are always the same three spots: `openEditForm()`'s special-case `if` chain, the `.add-new-btn` click
handler's `if/else` chain in `init()`, and a dedicated submit listener on the bespoke form (not
`handleFormSubmit`). Rows carry an optional hidden `id` field so the backend updates in place rather
than delete+recreate (`collectIdRows`/the manual row-reader attach `id` only when present). Existing
examples: Location (Ports/Serviceable Areas, ~line 329), Container (Sizes/Classes), Special Charges
(per-unit rates, backend-driven multi-rate model added 2026-10-20 — table column changed from a flat
`base_value` money cell to a `rates.map(r => \`${r.unit}: ${money(r.base_value)}\`).join(', ')`
summary). The Unit option list for rate rows is sourced from `/api/listofval/unit` (raw array
response, not `{success,data}` — matches the same endpoint's shape already relied on in
`logic_prospect_request_proposal.js`'s RFP ancillary-services modal, which also had to switch from
reading `charge.base_value` to resolving `charge.rates.find(r => r.unit === pickedUnit)`.

**Why:** keeps the generic engine simple for the common case while giving nested-row entities full
control, consistent with how Location/Container already worked — matching that exact pattern was an
explicit brief requirement, not a judgment call.

**How to apply:** if another maintenance entity needs multi-row children (or an existing flat entity
grows one), read the Location implementation first and mirror its three wiring points rather than
trying to extend ENTITY_CONFIG's generic field renderer to support nested arrays.
