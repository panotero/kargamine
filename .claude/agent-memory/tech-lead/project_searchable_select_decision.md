---
name: searchable-select-decision
description: Why kargamine has a hand-rolled searchableSelect.js instead of Select2/Choices/TomSelect
metadata:
  type: project
---

Port/location `<select>`s across CRM/Proposal flows were converted to type-to-search comboboxes on 2026-08-19 using a custom vanilla-JS component (`resources/js/searchableSelect.js`), NOT a library.

**Why:** The user explicitly decided against adding a new npm dependency. The app ships everything through the single `resources/js/app.js` Vite entry with no per-page bundling, so a library would load on every page for a handful of fields. A full-repo grep confirmed no Select2/Choices/TomSelect already existed.

**How to apply:** Don't propose adding a select library in future work — extend `makeSearchableSelect` instead. The same "build it small in-house rather than add a dep" instinct likely applies to other small UI widgets in this project. Component API is documented in CLAUDE.md.

Related: [[nullable-class-size-pipeline]]
