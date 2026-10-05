---
name: project-prospect-module-untracked-in-git
description: The entire Prospect/ProposalRequest module (CRM Leads → Prospects rename + everything since) is uncommitted/untracked as of 2026-09-25 — git diff/log show nothing for these files.
metadata:
  type: project
---

As of 2026-09-25, `app/Http/Controllers/ProspectController.php`, `app/Models/Prospect*.php`, `app/Models/ProposalRequest*.php`, all `2026_09_1*`/`2026_09_2*` migrations, and `resources/js/logic_prospect_*.js` / `resources/views/components/{prospect,request-proposal,add-origin-destination,proposal-request-summary}-modal.blade.php` are all **untracked** (`git status` shows `??`, `git ls-files` returns nothing for them). `MODULES.md`/`CLAUDE.md`/`VISUALS.md` are tracked and modified (`M`), but the modal/controller/model files backing the CRM Leads→Prospects rename plus everything built on top of it (multi-request lifecycle, wizard, Individual/Corporate toggle, etc.) were never committed.

**Why it matters for QA:** `git diff HEAD -- <file>` and `git log -p -- <file>` return **nothing** for any of these files, even when reviewing a "just-completed set of changes" — there is no prior committed version to diff against. Don't waste time trying to isolate "what changed in this session" via git for this module; read the current file state directly and evaluate correctness against the brief instead.

**How to apply:** When asked to review Prospect/CRM/ProposalRequest work, expect `git diff`/`git log` to be silent for the touched files and go straight to reading + `grep`. If asked to isolate exactly what's new vs. pre-existing in this module, say so explicitly rather than presenting a git-diff-based answer as ground truth. See also [[project-broken-test-suite-sqlite-baseline]].
