---
name: feedback-currency-input-collector-gotcha
description: converting an input to .currency-input/.phone-input is only half the job — every generic [data-field] collector it flows through must also be checked for a matching parseCurrencyValue/parsePhoneValue, or commas/spaces leak into the API payload
metadata:
  type: feedback
---

When converting a bare `type="number"` (or plain `type="text"`) input to `.currency-input` or
`.phone-input`, the live-formatting class alone does nothing for correctness — it's whatever reads
`el.value` at submit time that matters. This codebase has several *generic* `[data-field]` loop
collectors (e.g. `collectFormPayload()` in `logic_prospect_request_proposal.js`,
`collectContainerFormPayload()`/`collectTruckingFormPayload()` in `logic_prospect_modal.js`,
`collectRows()` in `clientMasters.blade.php`) alongside *one-off* `modal.querySelector('#id').value`
reads scattered in the same files (e.g. the Charter form's `weight` field, Charter Ports modal's
`cpmCargoesLoading`/`cpmPortChargeAmount`). The generic loops were already `.currency-input`-aware
in most places; the one-off reads generally were not and needed individual patches.

**Why:** found this by tracing every converted field in this task
([[project_canonical_formatters_datetime_currency_phone]]) to its submit site, exactly as the brief
demanded as "the #1 regression risk." Several one-off reads (request-proposal-modal's generic
`collectFormPayload` had no currency-input branch at all; Charter weight fields in both
`logic_prospect_modal.js` and `logic_prospect_add_modals.js`; `clientMasters.blade.php`'s
`cc-input-minqty` inline-edit and its `collectCpRateRows`/`collectRows` readers) were missed on
first pass and would have silently sent `"1,234"` strings to the backend.

**How to apply:** after adding `.currency-input` or `.phone-input` to any input, grep the same file
(and any file that shares the row-building helper via `window.prospectShared` or similar) for every
place that reads that field's `.value`, not just the nearest-looking collector function. Don't trust
that a generic `[data-field]` loop elsewhere in the file is actually what a given form submits
through — some forms mix the generic loop with manual field-by-field reads in the same
`collect*()` function.
