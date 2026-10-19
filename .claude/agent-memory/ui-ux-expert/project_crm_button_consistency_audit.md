---
name: project-crm-button-consistency-audit
description: 2026-10-12 audit of every button/clickable control across the CRM/Prospect module (crm.blade.php + all Prospect/RFP modals) for a button-taxonomy unification pass, CRM first before other modules
metadata:
  type: project
---

Coordinator is doing a module-by-module "make every button visually uniform with one
accent-color system" pass, starting with CRM (per explicit user instruction - CRM only,
other modules later). This was an AUDIT-ONLY task (no edits made) covering
`pages/crm.blade.php`, `components/{prospect-modal,prospect-info-modal,pm-add-{location,
contact,freight,trucking,charter}-modal,add-{location,contact,origin-destination,
requirement}-modal,request-proposal-modal,charter-ports-modal,ancillary-services-modal,
top-load-cargo-modal,charter-cargo-info-modal,product-specification-modal}.blade.php`
plus `logic_crm.js`/`logic_prospect_{modal,info_modal,add_modals,request_proposal}.js`.

**Key mechanical findings (exact taxonomy is in the delivered report, not duplicated here):**
- Three different padding/size tiers coexist for the same "primary commit" role depending
  on container (full-tab footer Save: `px-5 py-2`; modal-footer Save: `px-3 py-1.5 text-sm`;
  inline-popover Done: `px-3 py-1.5 text-xs`) - not inherently wrong (different weight makes
  sense for different contexts) but was never written down as an intentional 3-tier scale,
  so new buttons keep landing on a fourth ad-hoc size (e.g. `armAddRequirementBtn`/
  `rpmAddProductBtn`/`rpmSaveCompanyDetailsBtn` all use `px-4 py-2`, a tier nobody decided on).
- Cancel buttons almost everywhere (`almCancelBtn`, `acmCancelBtn`, `aodmCancelBtn`,
  `pmAlmCancelBtn`, `pmAcmCancelBtn`, `pmAfmCancelBtn`, `pmAtmCancelBtn`, `pmChmCancelBtn`,
  `pimSummaryCancelBtn`, `cpmCloseBtn`) are missing the `bg-zinc-50 dark:bg-zinc-800
  hover:bg-zinc-100 dark:hover:bg-zinc-700` fill+hover that the "+Add X" secondary buttons
  (same visual role per VISUALS.md) always carry - border-only, zero hover feedback. One
  exception already does it right: `psmCancelBtn` (product-specification-modal) has
  `hover:bg-zinc-50 dark:hover:bg-zinc-800` - that's the target to standardize toward, not
  a one-off to flag as wrong.
- Systemic missing `dark:text-orange-400` pairing on orange *text* (not bg) elements:
  active tab-nav state in all three tab idioms (`pm-tab-btn`/`pim-tab-btn`/`rpm-tab-btn`
  all do `text-orange-600` with no dark variant), plus every row-action text-link
  (`asm-edit`/`tlcm-edit`/`ccim-edit`/`cpm-edit` "Edit", `rpm-view-ancillary`/
  `rpm-view-topload`/`rpm-view-charter-*` "View", `pimSummaryEditBtn` "Edit", the
  Download PDF/Signed Copy `<a>` tags in `logic_prospect_info_modal.js`). This is the
  single highest-count violation by occurrence - one shared fix class would resolve ~15+
  sites. Chips (`rpm-od-chip`/`aodm-chip`) already get this pairing right - good reference.
- Two coexisting tab-nav visual idioms for the same "tab bar" role: ProspectModal's left
  rail uses boxed/bordered tabs (`border-2 ... bg-orange-50`), while ProspectInfoModal and
  RequestProposalModal both use horizontal underline tabs (`border-b-2 -mb-px`). Likely
  intentional (rail-vs-tab-bar are different VISUALS.md layouts) but worth a decision one
  way or the other during the unification pass rather than leaving it implicit.
- "Edit" text-links are missing `hover:` entirely in some spots (`asm-edit` etc: just
  `text-orange-600 text-xs font-medium`) while sibling "View" links on the same row/table
  do have `hover:text-orange-700` (`rpm-view-ancillary` etc) - same role, inconsistent hover.
- Destructive "Remove" row actions (`text-red-500 text-xs font-medium` + "✕ Remove" or
  bare "✕") are the most consistent button family in the whole module - confirmed identical
  across `logic_prospect_modal.js`, `logic_prospect_info_modal.js`,
  `logic_prospect_request_proposal.js`. Good pattern, don't touch it in the fix pass.
- `modal-close` header ✕ buttons are byte-identical across every single modal file read
  (`w-8 h-8 ... rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800`) - the one thing
  already perfectly standardized app-wide; worth using as the literal reference string for
  whatever shared taxonomy doc gets written.
- Button label drift for the identical "confirm this add-popup" role: "Save" (location/
  contact add popups) vs "+ Add Requirement" (freight/trucking/charter add popups, both in
  ProspectModal's own popups AND the standalone AddRequirementModal) - not a class/color
  issue but will look inconsistent once colors are unified, worth a product-copy pass too.
- Terminology drift unrelated to button color but affects the same buttons: page title
  says "CRM Leads", toolbar button says "+ New Prospect", JS empty-state button says
  "+ New Lead" (`logic_crm.js` `crmEmptyState()`) - leftover from the CrmLead -> Prospect
  rename (see CLAUDE.md's domain model note). Flagged as comprehension, not styling.

**Stale findings from the prior `project_prospect_proposal_request_module.md` review
(2026-09-25) that are now FIXED as of 2026-10-12 - don't re-flag these:**
- `esc()` now exists and is used in `logic_prospect_add_modals.js` (was previously absent).
- `addOdLocation`/`addSignatory` in `logic_prospect_request_proposal.js` now pass `button:`
  to their `apiCall` (double-submit guard present).
- `.rpm-od-chip`/`.aodm-chip` both now have `aria-pressed` + correct
  `dark:text-orange-400` pairing on their active state.
- `.pm-client-type-btn` no longer exists in the codebase at all (Individual/Corporate is
  now derived from Business Type via a hidden input, per the blade file's own comment) -
  don't look for it again.

**Still-open from that prior review:** `.pm-social-platform-btn` (Social Media source
popover's 4-choice picker) still toggles selection via `border-orange-500` color only, no
`aria-pressed` - confirmed still present at `logic_prospect_modal.js` ~line 448-452.

**How to apply:** if asked to re-audit this module after the fix pass lands, the fastest
mechanical re-checks are (1) grep `text-orange-600` across all CRM blade/JS files and
confirm every hit is paired with `dark:text-orange-400` on the same element, (2) grep
`Cancel</button>` / `Close</button>` in these files and confirm each has a `hover:bg-zinc-*`
class, (3) diff button padding values across every file in this module's scope to confirm
they now resolve to the agreed tier count.
