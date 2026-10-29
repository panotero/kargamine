---
name: project-rfp-additional-note-signed-doc-batch
description: 2026-10-22 review of a small RFP batch - additional-note textarea + Grand Total matrix column, prsmSignedSection file upload, cargo-detail sub-row in logic_client_proposals_shared.js
metadata:
  type: project
---

Reviewed four touched files from a just-completed bug/feature batch on the
Prospect/ProposalRequest module (see [[project-prospect-proposal-request-module]]
and [[project-proposal-requests-consolidation]] for the module's earlier audits):
`components/request-proposal-modal.blade.php` (new `#rpmAdditionalNotes` textarea +
Grand Total column on the Build Rate Matrix table), `components/proposal-request-summary-modal.blade.php`
(new `#prsmSignedSection` file-upload block), `logic_client_proposals_shared.js`
(new cargo-detail sub-row under each RFP rate row + `#cpmRfpAdditionalNote` display),
`logic_proposal_requests_mine.js` (toggle wiring only).

**Real bug found (blocker):** `logic_client_proposals_shared.js` `rfpCargoDetailLines()`
lines ~101-107 - the Route lines built from `origin_location`/`destination_location`
and `origin_port`/`destination_port` are interpolated WITHOUT `esc()`, while the
charter-ports branch three lines below (line 109) and every other field in the same
function (cargo_type, cargo_description, weight_unit, special_requirements, etc.)
DOES use `esc()`. This is a genuinely new unescaped-output gap in new code, not
inherited - same bug class as the add-origin-destination-modal gap noted in
[[project-prospect-proposal-request-module]]. The file does define and mostly use
`esc()` correctly (defined lines 69-77) - this is an isolated miss, not a missing-helper
situation.

**Good news / net improvement:** the new `#prsmSignedSection`
(`proposal-request-summary-modal.blade.php:32-43`) actually FIXES two bugs that exist
in the older sibling it was ported from (`#cpmSignedSection` in
`proposal_signed_contracts.blade.php:164-174`, flagged in
[[project-proposal-requests-consolidation]]): paired `dark:bg-zinc-800` with
`dark:text-zinc-100` (old one has bare `dark:text-zinc-900`, unreadable), and
orange-500 primary button (old one is `bg-green-600`). Net effect: the SAME
"Upload & Accept" action now looks different across two pages, with the NEW one
being the design-system-correct version - recommend fixing the old one to match the
new one, not the reverse, if asked to reconcile.

**Accessibility gap (inherited, both old and new):** neither `#prsmSignedFile` nor
`#cpmSignedFile` has a `<label for=...>` or `aria-label` - the preceding `<p>` is a
heading, not a programmatic label.

**Confirmed clean:** the Additional Note display block (`#cpmRfpAdditionalNote`/
`#cpmRfpAdditionalNoteText`) is byte-for-byte identical across all three pages
(`proposals.blade.php`, `proposal_requests.blade.php`, `proposal_signed_contracts.blade.php`)
and uses `.textContent` (not innerHTML) so no escaping gap there. The new cargo-detail
sub-row's layout/typography correctly mirrors the sibling fee-breakdown sub-row
(`text-[11px] text-zinc-500 dark:text-zinc-400`, same colspan/indentation). The new
Grand Total matrix column is column-identical across the Build Matrix tab and the
shared `buildRateMatrixRecapBlock()` recap (used by both the Confirmation tab and the
read-only My Requests summary modal) - good DRY, no drift. `rpmAdditionalNotes` is
correctly round-tripped into the submit payload and `rpmSubmitBtn` has its button-loading
guard.

**How to apply:** if this area is touched again, re-grep `esc(` in
`logic_client_proposals_shared.js` specifically around `rfpCargoDetailLines()` to
confirm the Route-line fix landed, and check whether `#cpmSignedSection`'s green
button / dark-on-dark input were ever reconciled toward the new orange version.
