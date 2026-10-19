---
name: feedback-diff-full-files-not-just-brief-scope
description: Always git diff the complete touched files, not just the hunks the brief claims were changed — briefs can omit scope creep, including privilege/role changes
metadata:
  type: feedback
---

Always run `git diff` on the full contents of every file listed as "changed" in a
brief, not just the areas the brief describes. Do not assume the brief's
description of what changed is exhaustive.

**Why:** Reviewing a "signed rate snapshot" feature (brief: new migration +
model + `attachSigned()` transaction fix), the full diff of
`app/Http/Controllers/ClientProposalController.php` and
`app/Models/ClientProposal.php` revealed an undisclosed privilege-escalation
change unrelated to the stated brief: a `'developer'` role was added alongside
`'superadmin'` as a bypass in `canBeApprovedByRm`, `canBeApprovedByManager`,
`canBeRejectedBy`, `canBeSignedBy`, and the `indexAll()` team-scoped
visibility query — letting any `developer`-role user approve/reject/sign/view
ALL client proposals regardless of `TeamService` team hierarchy. This directly
contradicts CLAUDE.md's documented rule ("superadmin bypasses this entirely" —
not developer) and was nowhere in the brief's "Files changed" list. Had I only
read the `attachSigned()` hunk the brief pointed at, this would have shipped
unreviewed.

**How to apply:** For every file in a brief's changed-files list, run
`git diff <file>` (whole file, not `-U0` or targeted grep) before concluding
the review. Treat any role/permission/visibility-check change found outside
the brief's stated scope as a top-severity finding to flag back to the
implementer/tech-lead, even if the rest of the diff is correct — scope creep
in authorization logic is a security concern, not a style nitpick.
