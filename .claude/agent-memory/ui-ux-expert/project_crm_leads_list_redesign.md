---
name: project-crm-leads-list-redesign
description: Status and direction of the CRM Leads list page shell (crm.blade.php, everything above the LeadInfoModal) redesign initiative
metadata:
  type: project
---

The CRM Leads list page shell (`resources/views/pages/crm.blade.php`, top of file down to
`<x-modal id="LeadInfoModal">`) — header, 7 status-count cards, Assigned Rep filter, and the
`tableCrm` leads table populated by `resources/js/logic_crm.js`'s `renderTable()` — was reviewed
independently on 2026-09-04, after the separate [[project_crm_lead_info_modal_redesign]] and
[[project_crm_lead_form_redesign]] initiatives (both already implemented/in-progress, out of
scope for this review). User is planning a mockup and asked for fresh structural review, not a
repeat of accessibility/color/dark-mode polish (already covered in an earlier full audit pass).

**Why:** the 7 equal-width status cards (`ALL` + 6 stages) each render a full-width colored bar
that doesn't scale with count, so despite looking like a proportion visual it's just 7
same-size colored buttons — no actual at-a-glance pipeline distribution. `ALL` is a "clear
filter" action wedged in as a peer stage card. The status cards' grid
(`grid-cols-2 md:grid-cols-3 lg:grid-cols-7`) also produces awkward orphaned-cell wrapping at
`md` width (7 into 3 = orphaned last card). Separately, the table's Status cell
(`renderStatusCell`, logic_crm.js ~291) renders plain text + tiny icon with no color, even
though the exact same status has a color mapping (`STATUS_BADGE`, logic_crm.js ~20) used by the
cards above and by the Lead Info modal's badge — an inconsistency within the same page. The
Last Activity column already computes a staleness signal (red dot >14 days, gray 7-14 days,
logic_crm.js `renderLastActivityCell` ~304) but it's a small dot in one column, not surfaced as
a row-level cue or a quick filter, despite "what needs attention today" being the page's core
job. `renderTable()`'s call into the shared `renderRemoteTable` doesn't pass a custom
`emptyMessage`, so a brand-new team with zero leads ever and an existing team with zero
filtered matches see the identical generic "No records found." with no CTA either way.

My recommendation (given in full 2026-09-04, not yet acted on):
1. Replace the 7-card grid with a single compact strip: one proportional horizontal segmented
   bar (segments sized by real share, ordered LEAD→QUALIFIED→OPPORTUNITY→NEGOTIATION, with
   WIN/LOST visually separated as closed-state badges rather than peers in the active-pipeline
   bar) plus small clickable count chips below acting as the filter toggles (reusing the
   existing `ring-2 ring-orange-500` active-state pattern). Move the `ALL`/total count out of
   the card row entirely into the page header as a plain stat, since it's a reset action, not a
   stage. This also removes the awkward grid-wrap responsive issue as a side effect.
2. Header: the team-scope indicator (`#crmScopeIndicator`, already wired via
   `loadAssignableUsersFilter` in logic_crm.js ~386, mirroring the modal's leader-scope work)
   already exists and satisfies that ask — don't re-add it, just note its current form (plain
   small gray inline text after the subtitle) is low visual weight for what should be a
   meaningful leader signal. New idea (not yet built): promote a computed "N leads need
   attention" stat/button into the header, feeding the same staleness data as #3.
3. Filter row: add a "Needs Attention" toggle chip next to the Assigned Rep dropdown, filtering
   to leads with no activity or activity >14 days old (data already computed client-side in
   `renderLastActivityCell`, just needs a filter param wired through). Visually group Assigned
   Rep + Needs Attention + search into one toolbar container instead of two floating controls.
4. Table: reorder columns to group identity (Contact/Company) first, then
   pipeline-ownership (Status + Assigned To adjacent), then timing (Last Activity, then Created
   last since it's least actionable). Fold Email/Mobile into a secondary line under Contact
   name rather than separate columns (frees width, matches the identity-grouping pattern
   already used in the redesigned Lead Info modal rail). Give the table's Status cell the same
   `STATUS_BADGE` color pill used elsewhere on this same page instead of plain text. Promote
   staleness from the small dot to a thin colored left-border row accent so stale rows read
   peripherally while scanning, not only when the eye lands on that one column.
5. Empty states: pass distinct `emptyMessage` values (or a custom empty-state block, matching
   the pattern already used for `proposalEmptyState()` inside the modal) for true zero-leads
   ("+ New Lead" CTA) vs. zero-filtered-results ("clear filters" CTA) — currently both hit the
   same generic "No records found." from `remoteTable.js`'s default.

**How to apply:** if a future session picks up the mockup or implementation, check whether the
single-segmented-bar direction (replacing the 7-card grid) and the "Needs Attention" filter
were accepted before assuming they're still the plan — this was a recommendation, not a
signed-off decision. Re-read `resources/views/pages/crm.blade.php` (top portion only — the
modal below it is a separately-tracked surface) and `resources/js/logic_crm.js`'s
`renderTable`/`renderCounts`/`loadAssignableUsersFilter` fresh, since this page may still be
under active edit.
