---
name: feedback-readonly-attach-preview-mirrors-form
description: When a chip-picker attaches an existing record (e.g. O&D location) via FK, the read-only confirm-preview must mirror the source form's exact field set/control types (disabled), not a compact label/value grid.
metadata:
  type: feedback
---

Built the Origin & Destination chip-picker (Request for Proposal wizard's O&D tab, and the
standalone Add Origin/Destination modal - both in `resources/views/components/` +
`logic_prospect_request_proposal.js` / `logic_prospect_add_modals.js`) with a compact label/value
preview grid (10 `<p>` pairs) under the chips, on the assumption that a read-only "here's what
you're about to attach" summary just needed to show the same data as the eventual table row. The
tech lead corrected this in the very next pass: the preview must instead be an actual form -
same field set, order, and control types (`<select>` vs `<input>`) as
`window.prospectShared.locationCardHtml()` (the Identity tab's live address-entry form) - just every
control `disabled` and pre-filled directly from the clicked chip, no PSGC cascade wired up. Primary
radio and Remove button are dropped since neither applies to a preview.

**Why:** the data model is a live FK reference (`prospect_location_id`), and the surrounding
Identity-tab convention for showing/editing an address is always the multi-field form layout, not a
condensed grid - a summary grid reads as "different, lesser" UI even when covering the same fields,
and breaks consistency with the rest of the modal.

**How to apply:** any future "attach an existing sub-record by picking a chip/row" UI (locations,
contacts, or similar FK-driven pickers) should default to a disabled/read-only rendering of that
record's *actual* entry-form markup, not a bespoke summary grid - re-use the live form's field
list/order/control-types and just add a disabled affordance
(`bg-zinc-100 dark:bg-zinc-800/60 cursor-not-allowed` alongside `disabled`), even though nothing is
actually editable or wired to cascades in that read-only context. Don't invent a shorter/prettier
custom preview layout unless explicitly asked for one.
