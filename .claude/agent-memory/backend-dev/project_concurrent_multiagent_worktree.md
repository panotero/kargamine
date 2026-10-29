---
name: project-concurrent-multiagent-worktree
description: This working tree is edited concurrently by other agents (frontend-dev etc.) in the same session set — full test-suite runs can show unrelated failures from their in-progress, uncommitted work.
metadata:
  type: project
---

Observed 2026-10-20 while verifying a narrow backend-only change (new `ResolvesLaneRates`
trait + `containerRate()` endpoint): `git status` at a task's start can already show dozens
of modified files across controllers/models/JS/Blade/migrations that this task never touched.
A `git stash`/`git stash pop` done mid-task to isolate "is this failure caused by my change"
surfaced unrelated uncommitted work-in-progress (e.g. a new untracked migration
`add_owner_id_to_prospects_table`, edits to `ProspectController`, `Prospect.php`,
`TeamService.php`, several `logic_prospect_*.js` files) — almost certainly another agent
(frontend-dev/tech-lead) working the same prospect/proposal feature set in parallel.

**Why:** `php artisan test` (full suite) picked up ~58 failures mid-task, far more than the
~17 "known unrelated" baseline from [[project_broken_test_suite_sqlite]]. Stashing this
agent's own 3-file change and re-running the one failing test still failed identically,
proving the failures were pre-existing/environmental (a SQLite "can't drop foreign keys"
migration error), not caused by this task's change — but the inflated failure count itself
came from other agents' concurrent, not-yet-stable edits sitting in the same tree.

**How to apply:** Don't treat a spike in full-suite failure count as evidence your own change
broke something without isolating it first (stash-and-rerun, or better, run only the
specific/filtered test for the area you touched). Prefer targeted verification — `php -l`,
`vendor/bin/pint` on just the files you touched, `route:list`, and `php artisan tinker` to
directly invoke the controller method/trait with real DB rows — over a full suite run when
the tree clearly has unrelated in-flight work (check `git status` first). Never `git stash`
destructively without immediately popping it back; confirm the pop succeeded and diff count
matches before reporting done.
