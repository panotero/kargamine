# VISUALS.md

Design language reference for kargamine. Read this before redesigning any screen — the goal is that every redesigned page feels like it was built by the same team on the same day, not like a patchwork of different apps stitched together.

This file documents **patterns**, not a component library. Where a pattern references real code, that code is the source of truth — if this file and the code ever disagree, trust the code and fix this file.

Scope note: this covers *visual/interaction* patterns. Backend conventions (response envelopes, route naming, team-hierarchy access, etc.) live in `CLAUDE.md` — don't duplicate those here.

## Foundation (already established — see `CLAUDE.md` for full detail)

- **Neutrals:** `zinc`. **Primary action:** `orange-500`/`orange-600` (theme-driven at runtime via `AppThemeSetting` — new hardcoded `orange-*` classes are fine, they track the picked theme color, not a fixed hex).
- **Cards:** `rounded-xl`. **Modals:** `rounded-2xl`.
- **Field labels:** `text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500`.
- **Dark mode:** OS/browser preference only (`dark:` utilities, Tailwind's `media` strategy). There is no working manual light/dark toggle — don't design around one existing.
- **Modals/slide-overs:** always `<x-side-modal>` or `<x-modal>`. Never hand-rolled overlay markup.
- **Long option lists:** `window.makeSearchableSelect()`. Never a new third-party select library.

## Color-coding conventions

Pick **one hue mapping per concept**, and reuse the exact same mapping everywhere that concept appears on screen — a status/type must never render as a colored badge in one place and plain text in another on the same page. If you introduce a color-coded concept that doesn't exist yet, add its mapping here once it's decided.

### Lead pipeline status (`STATUS_BADGE`, `resources/js/logic_crm.js`)

| Status | Hue |
|---|---|
| LEAD | gray |
| QUALIFIED | indigo |
| OPPORTUNITY | purple |
| NEGOTIATION | amber |
| WIN | green |
| LOST | red |

Use this exact mapping anywhere a lead's pipeline status needs a color: table badges, the pipeline distribution bar.

### Container/cargo type (`CONTAINER_TYPE_DOT_COLOR`, `resources/js/logic_prospect_modal.js`)

| Type | Hue |
|---|---|
| Container Van (CV) | orange |
| Flatrack (FR) | amber |
| Reefer Van (RF) | cyan |
| Loose Cargo (LC) | purple |
| Rolling Cargo (RC) | blue |
| Trucking (TR) | emerald |
| Charter (CH) | rose |

A small colored dot + matching text color, not a filled badge — this sits next to the brand orange elsewhere on the same cards, so it stays a quiet identifier rather than competing for attention. Trucking/Charter have no prior mapping elsewhere in the app; their hues are defined in `LINE_ITEM_TYPE_META` (`resources/js/logic_prospect_request_proposal.js`, the Request for Proposal wizard's Products recap) — reuse those two exact hues if either type ever needs a dot/badge somewhere else.

### Semantic/urgency color (separate from brand color)

- **Red/danger:** stale (no activity in 14+ days), a real blocking gap (e.g. no proposal on a Negotiation-stage deal), missing required data.
- **Amber/warning:** aging but not yet urgent (7–14 days since activity), "in progress, keep an eye on it."
- **Green:** success, completed, healthy state.
- **Never reuse brand orange for these.** Orange means "primary action" or "brand accent" everywhere else on the page — if a stale-lead indicator is also orange, it visually disappears among all the other orange buttons and highlights on the same screen.

### Timeline entry type (`TIMELINE_DOT_COLOR`, formerly `resources/js/logic_crm.js`)

Note: zinc, Status Change: amber, other activity types: blue (default). Keep new activity types on the `DEFAULT` (blue) dot unless there's a real reason to give a type its own color. `TIMELINE_DOT_COLOR` itself was removed along with the dead LeadInfoModal it served (see "Merged, type-tagged timeline" below) - there's no live activity/notes timeline in the app right now, but this mapping is worth restoring verbatim if one gets rebuilt.

## Component patterns

### Accent callout card

For the single most important thing on a screen the user should not miss.

```html
<div class="border-2 border-orange-400 dark:border-orange-600 bg-orange-50 dark:bg-orange-950/20 rounded-xl p-4">
```

Used for: the "minimum to keep this record" callout in a long form (originally the retired `crmLeadForm.blade.php` New Lead form; reuse the treatment for the equivalent hint in any long form/modal). **Use sparingly — one per screen, max.** If everything is highlighted, nothing is.

### Required-field accent

```html
<div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
```

Applied to the wrapper `<div>` of any field carrying a `req-asterisk` span (originally established across the retired `crmLeadForm.blade.php`; carried forward into `components/prospect-modal.blade.php` and the client forms). Lets required fields be scanned at a glance across a whole section instead of hunting for a small `*` next to each label. Apply this to every new required field going forward — don't ship a required field with only the asterisk.

### Two empty-state treatments — pick deliberately, don't default to one

**Quiet** (nothing to do, that's fine): plain centered gray text, the shared `emptyState()` helper.

**Prompting** (a real actionable gap): dashed accent border, tinted background, embedded CTA.

```html
<div class="border-2 border-dashed border-orange-300 dark:border-orange-800 bg-orange-50 dark:bg-orange-950/20 rounded-xl p-6 flex flex-col items-center text-center gap-2">
    <span class="text-[11px] font-semibold uppercase tracking-widest text-orange-600 dark:text-orange-400">⚠ Action Needed</span>
    ...
    <button>+ New Proposal</button>
</div>
```

Established as `proposalEmptyState()` in `logic_crm.js` (no proposal yet on a Negotiation+ lead) - removed with the dead LeadInfoModal it belonged to; see `locationsPromptingEmptyState()` in `logic_prospect_modal.js` for the current live example. Ask: does this empty state represent something the user should actually go fix, or is it just "nothing here yet, carry on"? Prompting is for the former only — overusing it trains people to ignore it.

### Rail + tabbed pane — dense detail views

Established in the Prospect modal (`components/prospect-modal.blade.php`, 4 tabs: Identity → Contact Information → Requirements → Request for Proposal; the earlier `crm.blade.php` `LeadInfoModal` used the same pattern and is now superseded). Use when a single record has (a) reference data that's rarely re-read or edited, and (b) several substantial "working" sections that don't all need to be visible at once.

- **Left rail** (fixed width, ~19rem): identity/reference data, grouped by edit frequency. The most-referenced group stays always-visible; secondary groups go behind native `<details>` disclosures (open or closed by default depending on how often that group actually matters).
- **Right pane:** a tab bar plus one shared scroll region — not five separately-scrolling cards. Order tabs by what's highest-stakes for the task at hand, not by data-model order (e.g. Proposals before Requirements before Activity, because this modal only opens for deals already past qualification).
- **Header stat strip:** 3–5 "ask this first" facts, horizontal row divided by thin vertical borders, small uppercase label above a bold value. Reserve for facts that would otherwise require opening a sub-section to find.

### Guided multi-step wizard — long forms

Established in the (now-retired) New Lead page form `crmLeadForm.blade.php`; the CRM intake it served was replaced by the rail+tabs Prospect modal (see above). The wizard/stepper pattern below still stands for any future long single-page form. Use once a single logical save spans roughly 15+ fields across genuinely distinct sub-topics.

- Numbered-circle stepper; each step gets a checkmark once its parent save has actually succeeded (don't fake partial completion for sub-steps that aren't independently persisted).
- An **always-visible hint line** under the stepper explaining why a locked step is locked. Never rely on a `title` tooltip on a disabled button as the only explanation.
- If there's a clear "the 2–3 fields that matter most, if nothing else gets filled in," give them the accent callout card treatment (see above) inside the relevant step.

### "Same as X above" mirror pattern

Established for Authorized Signatory mirroring Contact Information. Use whenever a form has two field-groups of identical shape for two different people/entities, and one commonly matches the other in practice.

- Checkbox defaults to whichever state matches the common real-world case (here: checked, since the contact person is often the signatory).
- **Checked:** show a one-line live summary; the real fields stay in the DOM (hidden, not removed) and are kept in sync via `input`/`change` listeners on the source fields — never a submit-time copy step, since the save handler does a blind `FormData` collection.
- **Unchecked:** reveal the full fields, pre-filled with whatever was last mirrored, as a starting point rather than blank.

### Exclusive-expand repeatable list

Established for booking-requirement cards (Stage 2 of the retired New Lead form, and the read-only display in the old Lead Info modal). Use for any repeatable form entry once there can be 2+. **Intentional deviation:** the Prospect modal's Requirements tab does NOT use this — it captures containers into a dash-filled wide table instead (see "Dash-filled wide capture table" below), because that tab needs every container field visible side-by-side across many rows for comparison, which the collapse-to-summary pattern actively works against.

- **1 item:** always expanded, no collapse chrome — nothing to scan yet.
- **2+ items:** each collapses to a compact single-line summary (colored type dot + label + key facts + a count/qty badge). Opening one item auto-collapses any other open item. **Adding a new item also auto-collapses the existing ones** — this is easy to miss (it was a real gap caught during implementation): if you only wire exclusivity into the manual toggle-click handler and not into the "add new item" flow, every added item stays open and you recreate the "wall of fields" the pattern exists to prevent.

### Anchored conditional popover — reveal follow-up fields on a choice

Established for the Prospect Source field in the Prospect modal's Identity tab (`components/prospect-modal.blade.php` / `logic_prospect_modal.js`). Use when picking a value in a `<select>` needs one or two follow-up fields that only apply to that value, and inlining every possible follow-up field would clutter the form.

- On selecting the triggering option, open a small popover **anchored to the field** (not a nested `<x-modal>`/`<x-side-modal>` dialog) — matching the existing inline-edit popover pattern. It carries just the follow-up input(s): e.g. Referral → "Referred By" text; Social Media → a small fixed choice list (Facebook/Instagram/Tiktok/Website); Others → "Specify Source" text.
- The follow-up value is folded back into a single stored string (`"Referral - <name>"`, `"Social Media - <platform>"`, or the raw text) and decoded back into select-value + popover-field on load — the DB column stays one string, the popover is purely presentational.
- Dismiss on outside-click / Escape, return focus to the trigger, and keep the select's value authoritative if the popover is cancelled.

### Dash-filled wide capture table — many-column repeatable rows

Established for the Prospect modal's Requirements tab container capture (`components/prospect-modal.blade.php`). Use when a repeatable entity has many (15+) fields that reviewers genuinely need to compare across rows at a glance, so collapsing rows to summaries (the exclusive-expand pattern) would hide the very thing being compared.

- A capture form above, a real `<table>` below; each add appends a `<tr>`. The header is the full field set.
- Fields that don't apply to the current row's type (driven by a `TYPE_FIELD_VISIBILITY`-style map) render a literal `-` in that cell rather than an empty cell — an empty cell reads as "missing/forgotten", a dash reads as "not applicable here."
- Because the table is very wide, it must live in a horizontal-scroll container; keep the header readable and give each row a delete affordance. Keep the column order in the markup in sync with the JS row-builder's cell order.

### Route/flight-card pattern — origin → destination data

Established for the read-only booking-requirement display (`renderContainers()`, formerly in `logic_crm.js`) - removed along with the dead LeadInfoModal it belonged to; no live implementation exists right now. Use for any record whose defining relationship is A → B (a route, a transfer, a handoff).

- Origin and destination as two symmetric columns with a connecting arrow between them — not two label/value rows in a generic grid.
- Secondary per-side detail (e.g. handling instructions) sits directly under its side, **unlabeled** — position carries the meaning instead of repeating "Origin Handling" / "Destination Handling" as text labels.

**Documented exception:** the Request for Proposal wizard's Products tab (`components/request-proposal-modal.blade.php`, each product's "Route" sub-block) is a live implementation of this two-column/connecting-arrow shape, but deliberately breaks the "unlabeled" rule above — its per-side fields (Origin/Port of Origin, Destination/Port of Destination) ARE labeled, because they're real pick-fields (searchable selects) the user is actively filling in, not passive display values. Its connecting arrow also uses the orange brand accent (`text-orange-500`) rather than a muted zinc arrow, intentionally — inside a data-entry form the arrow doubles as a direction cue the user should notice while picking origin/destination, whereas the muted zinc arrow in the passive list-view version of this pattern is just decoration once the data already exists. On narrow viewports the two columns stack (`flex flex-col md:flex-row`) and the arrow rotates 90° to point down instead of right.

### Merged, type-tagged timeline

Established for the Lead Info modal's Activity tab (merging what used to be separate Activity and Notes cards) - that modal is now removed, so there's currently no live implementation of this pattern. Use whenever two or more "log entries about the same thing over time" data types are conceptually one feed.

- One reverse-chronological list, one add control with a type toggle — not N separate lists with N separate add buttons.
- Small colored dot per entry (see `TIMELINE_DOT_COLOR` above) on a vertical connecting line.

### Segmented distribution bar

*Approved in the CRM list page mockup, not yet built as of this writing — check `resources/views/pages/crm.blade.php` before assuming this exists in code.*

For showing proportional distribution across a small number of categories (e.g. pipeline stage counts). A row of equal-size cards with a decorative full-width color bar inside each one is **not** a distribution view, no matter how it looks — if showing proportion is the point, segment widths must be real percentages of the total.

## Sidebar category grouping

Top-level nav items (`nav_menus.category`, set on the Developer Option → Menus admin page) can be grouped under a section label in the vertical sidebar (`resources/js/navmenu.js`'s `groupMenusByCategory()`/`createCategoryLabel()`). Uncategorized items render first, flat, with no label — grouping is opt-in per item, never required. Categorized items follow as one section per category, in first-appearance order (no separate ordering field — it just follows each item's own `menu_order`).

The label itself reuses the standard field-label style above (`text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500`), consistent with its other use as the collapsed-rail flyout's parent-name heading. On the collapsed icon-only rail the text label hides (nothing to show at that width) and a thin `border-t` divider marks the group boundary instead — see `createCategoryDivider()` and the `.cat-label`/`.cat-divider` toggles in `applyCollapsed()`.

This grouping only applies to the vertical sidebar. The horizontal top-nav layout (`nav_layout === 'top'`, `createTopNavItem()`) intentionally stays flat/ungrouped — revisit if that layout ever needs the same treatment.

## Layout-preference awareness

Some pages must adapt their *own* internal layout based on the user's saved `nav_layout` preference (Profile → Navigation Layout: side/top), not just the global nav shell. Established in the New Lead form: when `nav_layout === 'top'`, the form's own horizontal stepper switches to a vertical left-side rail, since the global top nav already occupies the horizontal space at the top of the screen.

```php
@php $navLayout = Auth::user()->nav_layout ?? 'side'; @endphp
```

Check this (same pattern as `dashboard.blade.php`) before assuming a horizontal top-of-content control is always the right call — anywhere a page's own chrome would otherwise stack a second horizontal bar under the app's top nav is a candidate for this same left-rail treatment.

## Anti-patterns (caught and fixed during recent redesigns — don't reintroduce)

- Don't give an empty state generic gray text when it represents a real actionable gap. Don't make every empty state "prompting" either — that trains people to ignore the accent.
- Don't let the same status/type value render as plain text in one place and a colored badge somewhere else on the same page.
- Don't bury a required field inside a section that's collapsed by default.
- Don't build two structurally-identical field blocks (e.g. two people's contact details) as fully independent when one commonly mirrors the other — offer the "same as" shortcut instead.
- Don't wire "exclusive expand" or similar list behavior into only one entry point (e.g. the toggle click) when there's a second entry point (e.g. adding a new item) that needs the same behavior to actually deliver on the pattern's intent.
