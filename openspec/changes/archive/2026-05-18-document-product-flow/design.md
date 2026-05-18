## Context

Brainstorm and grill-me skills currently produce their output inline in the chat. This works in a single session but breaks across sessions: `/clear` resets the context and the briefing is lost. The product-level flow (Brainstorm → Align → Roadmap) is by nature multi-session — a developer brainstorms one day, aligns the next. Without persistent artifacts, the skills are effectively single-session tools.

The `openspec/product/<topic>/` convention mirrors the existing `openspec/changes/<name>/` pattern: topic-scoped folders committed to the repo. Files survive `/clear`, survive machine changes, and can be reviewed by teammates.

`AGENT_MISSION.md.example` duplicates the per-change flow (git checkout, new, continue, apply…) that already lives in CLAUDE.md via `openspec-flow.md`. A developer reading both files sees contradictory or stale information. The file should be a self-contained briefing: _what to work on_, _which mode_, _what to produce at the end_. Everything else is deferred to CLAUDE.md.

All thinking-heavy commands (`/brainstorm`, `/grill-me`, `/recap`, `/opsx:explore`) currently run on whatever model the session uses. Claude Code slash commands support `model: opus` in YAML frontmatter to pin per-command model selection. This is the correct mechanism — no user action required, no session config to remember.

## Goals / Non-Goals

**Goals:**
- Skills write their outputs to repo files, not only to chat
- Product-level section in `openspec-flow.md` is self-sufficient: storage locations, context-reset convention, ROADMAP format
- AGENT_MISSION.md.example is < 30 lines with no duplicated flow content
- Four commands pinned to Opus 4.7 via frontmatter

**Non-Goals:**
- Changing the content or question logic of the brainstorm/grill-me skills
- Creating an actual `openspec/ROADMAP.md` for this project (user decision)
- Enforcing plan mode for product-level phases
- Patching other opsx commands beyond explore.md

## Decisions

**D1: `openspec/product/<topic>/` as storage root**

Alternatives considered: `docs/product/`, flat files in `openspec/`, per-session `~/.claude/` files.

`openspec/product/` is chosen because: it follows the existing `openspec/changes/` naming pattern, stays within the OpenSpec namespace, and is version-controlled. `docs/` is reserved for project-facing documentation. Flat files in `openspec/` would pollute the root alongside `ROADMAP.md` and `config.yaml`.

**D2: Skills write the file, not the user**

The skill instructs Claude to use the Write tool to persist `briefing.md` / `aligned.md` at the end of the session. The user does not run a separate save command. The output is also still shown inline so the developer can read it without opening the file.

**D3: `grill-me` reads `briefing.md` if it exists, does not require it**

`/grill-me` can also stress-test a proposal.md or a design decision unrelated to brainstorm. Reading `briefing.md` is a "if present, load context" step, not a hard dependency. This keeps the skill useful standalone.

**D4: `opsx/explore.md` patch is documented, not automated**

`opsx/explore.md` is vendor-managed by `npx openspec update`. Adding `model: opus` is a local patch that will be overwritten on the next update. Precedent: the earlier `npx openspec` patch (chore: pin openspec CLI locally and patch skills to use npx). The patch is documented in `docs/dev-setup.md` as a "known post-update patch" so developers know to re-apply it after `openspec update`.

**D5: ROADMAP.md template inline in `openspec-flow.md`, no `.example` file**

ROADMAP.md is a simple checklist — three sections, one format. Documenting it inline avoids another `.example` file at the root of `openspec/`. AGENT_MISSION.md.example exists because it has mode settings and a mandatory stop format that are non-obvious; ROADMAP.md has no such complexity.

## Risks / Trade-offs

**Skill writes a file without user confirmation** → The Write step appears as a tool call in the UI; user can deny it. Skill should state the target path before writing so user knows what will happen.

**opsx/explore.md patch lost on update** → Documented as known patch. Low risk: developers run `openspec update` rarely and the `docs/dev-setup.md` post-update checklist will catch it.

**`openspec/product/` folder has no schema validation** → Unlike `openspec/changes/`, product artifacts are free-form. No validation needed — they're thinking documents, not structured contracts.
