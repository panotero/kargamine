---
name: project-canonical-formatters-datetime-currency-phone
description: resources/js/customFunctions.js now has canonical formatDate/formatDateTime, .currency-input, and new .phone-input helpers — app-wide convention as of 2026-10-05
metadata:
  type: project
---

As of 2026-10-05, `resources/js/customFunctions.js` is the single source for three live-formatting
conventions used across the whole app. A backend pass was concurrently stripping ad-hoc date casts
from Eloquent models so date/datetime fields serialize as raw ISO8601 — the JS helpers below are
what keep that off the screen.

**Dates/datetimes** — `window.formatDate(iso)` → `"October 05, 2026"`, `window.formatDateTime(iso)`
→ `"October 05, 2026 01:00 PM"` (note: no comma before the time — don't use `toLocaleString`'s
default, it inserts one). Both guard empty/invalid input by returning `""`. Any remote-table column
whose `key` is a date/datetime field and has no `render:` callback will leak raw ISO8601 — always
pair such keys with `render: (r) => formatDate(r.field)` / `formatDateTime(...)`.
`charter_start_date`, `charter_end_date`, `loading_date`, and `delivery_date` are a deliberate
exception — the backend keeps these as plain `Y-m-d` (they're read into native
`<input type="date">` and round-tripped as payload strings), so do not run them through
`formatDate`.

**Currency/numeric** — pre-existing `.currency-input` pattern (`type="text"`, live comma-grouping,
`window.parseCurrencyValue()` to strip before submit). Converted most `type="number"` inputs across
the app (quantities, weights, declared values, rates, amounts) to this pattern. Left untouched on
purpose: temperature fields, percent fields (tax/withholding), day-count/laytime spinners, vessel
dimension specs (length/beam/draft/speed/engine power), and small conversion-factor fields
(Equivalent TEU, Convan count) — none of these benefit from thousand-grouping. See
[[feedback_currency_input_collector_gotcha]] for the #1 regression risk when adding more of these.

**Phone** — new `window.formatPhoneDisplay(value)` / `window.parsePhoneValue(value)` + a delegated
`.phone-input` class, built mirroring the `.currency-input` block (same caret-preservation
technique). Phone numbers are stored **digits-only**; formatting is display/entry sugar only,
stripped via `parsePhoneValue()` at every submit site. PH mobile (11 digits starting `09`) formats
as `XXXX XXX XXXX`; everything else groups in blocks of 4. The Prospect modal's contact-channel
value input (`logic_prospect_modal.js`, `channelRowHtml()`/`addChannelRow()`, exposed via
`window.prospectShared.addChannelRow` and reused by `logic_prospect_add_modals.js`'s AddContact
modal) is shared between mobile/landline/email — a `bindChannelTypeToggle()` helper toggles the
`.phone-input` class and reformats on every `channel_type` change; email never gets the class.

Legacy `resources/js/formatter.js`'s `.format-mobile` class (used only in the orphaned
`components/new-proposal-modal.blade.php`, see [[feedback_crm_scope_discipline]]) was deliberately
left alone and not extended — it's dead code, not the canonical pattern.
