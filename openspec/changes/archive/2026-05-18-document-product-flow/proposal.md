## Why

The product-level flow (Brainstorm → Align → Roadmap) exists as skills and guideline stubs but has no persistent storage convention: outputs are shown inline and lost on `/clear`, context-reset between phases is undocumented, ROADMAP.md has no defined format, AGENT_MISSION.md.example duplicates the per-change flow already in CLAUDE.md, and thinking-heavy commands run on whatever model the session happens to use.

## What Changes

- `brainstorm` skill: writes product briefing to `openspec/product/<topic>/briefing.md` instead of inline-only
- `grill-me` skill: reads `briefing.md` if present, writes shared-understanding to `openspec/product/<topic>/aligned.md`
- `openspec-flow.md`: expand product-level section with storage convention, context-reset note, ROADMAP.md template, and new "Autonomous Mode (AGENT_MISSION)" section
- `AGENT_MISSION.md.example`: slimmed to ~25 lines — remove duplicated per-change flow, keep only pointer to ROADMAP + mode setting + mandatory stop format
- Commands: add `model: opus` frontmatter to `brainstorm.md`, `grill-me.md`, `recap.md`, `opsx/explore.md`
- `docs/dev-setup.md`: document `opsx/explore.md` as a known post-`openspec update` patch

## Capabilities

### New Capabilities

_(none — all changes are to existing capabilities and conventions)_

### Modified Capabilities

- `brainstorm-skill`: new requirement that output is persisted to `openspec/product/<topic>/briefing.md`
- `grill-me-skill`: new requirements that input reads `briefing.md` if present and output is persisted to `openspec/product/<topic>/aligned.md`
- `git-workflow`: four new documentation requirements — product-level storage convention, context-reset between phases, autonomous-mode AGENT_MISSION reference, model-selection per command type

## Impact

- `.claude/skills/brainstorm/SKILL.md` — storage step added
- `.claude/skills/grill-me/SKILL.md` — read briefing step added, storage step added
- `.claude/commands/brainstorm.md`, `grill-me.md`, `recap.md` — model: opus
- `.claude/commands/opsx/explore.md` — model: opus (vendor-managed, documented as known patch)
- `.ai/guidelines/openspec-flow.md` — new sections → triggers CLAUDE.md rebuild
- `openspec/AGENT_MISSION.md.example` — slimmed down
- `docs/dev-setup.md` — known patches section
- OpenSpec specs: brainstorm-skill, grill-me-skill, git-workflow (delta files in this change)
