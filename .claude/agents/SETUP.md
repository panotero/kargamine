# Agent setup

## 1. Install

Copy the three files into your Laravel project root:

```
your-project/
└── .claude/
    └── agents/
        ├── tech-lead.md      (opus)
        ├── backend-dev.md    (sonnet)
        └── frontend-dev.md   (sonnet)
```

Commit them — project agents are meant to be version-controlled.

If `.claude/agents/` didn't exist before your Claude Code session started,
**restart the session** so it gets picked up.

## 2. Run

The senior dev runs as your **main session**, not as a subagent:

```bash
claude --agent tech-lead
```

The startup header shows `@tech-lead` when it's active.

To make it the default for this project, add to `.claude/settings.json`:

```json
{
  "agent": "tech-lead"
}
```

The CLI flag overrides the setting if both are present.

## 3. Use it

Just describe the feature:

```
Add bulk rate import to the freight system — CSV upload, preview table,
then commit. Plan it and delegate.
```

tech-lead reads the code, writes the plan and the API contract, then dispatches
`backend-dev` and `frontend-dev` with full briefs.

You can also force a specific one:

```
@"backend-dev (agent)" add the RateImport model and migration
```

## Why the senior dev is the main session

By default a subagent **cannot spawn other subagents**. A `tech-lead` defined as a
subagent would just do all the work itself and return one summary.

Running it as the main session with `--agent` means it gets the `Agent` tool, so the
`tools: Agent(frontend-dev, backend-dev)` allowlist in its frontmatter actually works —
it can spawn those two and nothing else.

### If you want tech-lead as a subagent instead

Add to `.claude/settings.json`:

```json
{
  "env": {
    "CLAUDE_CODE_MAX_SUBAGENT_SPAWN_DEPTH": "2"
  }
}
```

Two layers: your session → tech-lead → frontend/backend. Note that the
`Agent(frontend-dev, backend-dev)` type restriction is **ignored** on this path —
nested subagents can spawn any type. Trade-off: you lose visibility into the middle
layer, since only tech-lead's final summary comes back to you.

## Cost note

Opus on the main session means all your normal chatter runs on Opus too. If that's
more than you want, run the session on Sonnet and set `model: opus` only on a
planning subagent — you lose the orchestration, but planning still gets Opus.

## Things worth knowing

- **Subagents start with empty context.** They can't see your conversation or each
  other. Everything goes in the delegation prompt. Both agent files tell them to
  report back thoroughly for this reason — that report is the only channel back.
- **CLAUDE.md still loads** into custom subagents (unlike the built-in Explore/Plan
  agents), so shared project rules there apply to all three.
- **Editing agent files** takes effect within a few seconds, no restart needed —
  except when creating the `agents/` directory itself.
- `memory: project` writes to `.claude/agent-memory/<agent-name>/`. Commit it to share
  the accumulated knowledge, or switch to `memory: local` to keep it out of git.
- Backend and frontend can run in parallel, but only once tech-lead has locked the
  API contract. Otherwise they drift and you get a response shape mismatch.
