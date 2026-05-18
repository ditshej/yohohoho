## Why

The current OpenSpec flow starts at the change level (`/opsx:new`), but there is no defined process for the product level that comes before it. When starting a new feature or product increment, there is no skill to help explore and articulate ideas, no shared-understanding step to align developer and AI before writing specs, and no post-review step to ensure the developer actually understands what was built.

This leads to:
- Jumping into change specs without a clear product vision
- Misaligned assumptions between developer and AI that surface late
- Changes that pass CI and review but the developer cannot fully explain

The YouTube developer framework (brainstorm → align/grill-me → plan → implement → recap → refactor → archive) addresses all three gaps.

## What Changes

Three new Claude Code skills + slash commands:

1. **`brainstorm`** — product-level ideation. Facilitates open-ended exploration of what to build, outputs a product briefing MD that feeds into the roadmap and future OpenSpec changes.
2. **`grill-me`** (align phase) — interview-style skill. AI asks probing questions one at a time until both developer and AI share a clear understanding of the plan. Outputs a shared-understanding MD.
3. **`recap`** — post-review, pre-sync step. After AI review, the AI explains the feature in three sections: how it works, the step-by-step flow, and a Mermaid diagram. Forces the developer to verify understanding before archiving.

Additionally:
- **Refactor phase documentation**: the existing `simplify` skill is positioned with a three-question checklist in `openspec-flow.md` as the explicit refactor step.
- **`openspec-flow.md` extended**: product-level section (Brainstorm → Align → Roadmap), recap and refactor steps added to the change-level workflow.
- **`docs/dev-setup.md` §17-18 updated**: ROADMAP and AGENT_MISSION sections completed.

## Capabilities

### New Capabilities
- `brainstorm-skill`: New `/brainstorm` command/skill for product-level ideation, outputting a product briefing document
- `grill-me-skill`: New `/grill-me` command/skill for the align phase, outputting a shared-understanding document
- `recap-skill`: New `/recap` command/skill for the post-review step, outputting feature explanation with Mermaid diagram

### Modified Capabilities
- `git-workflow`: Extend the flow documentation to include product-level phases and recap/refactor steps

## Non-goals

- Building a vertical-slicing skill (roadmap decomposition) — this will be part of a future change or handled manually
- Modifying any of the 11 OpenSpec skills — those come from the OpenSpec package
- Building an automated AGENT_MISSION runner — the AGENT_MISSION.md remains a human-authored template
- Changing the `openspec/config.yaml` limits or schema

## Impact

New files:
- `.claude/skills/brainstorm/SKILL.md`
- `.claude/skills/grill-me/SKILL.md`
- `.claude/skills/recap/SKILL.md`
- `.claude/commands/brainstorm.md`
- `.claude/commands/grill-me.md`
- `.claude/commands/recap.md`

Modified files:
- `.ai/guidelines/openspec-flow.md` (product-level section + recap/refactor steps)
- `docs/dev-setup.md` (§17-18 completed, §7 refactor step added)
- `CONTRIBUTING.md` (brief mention of brainstorm/grill-me as pre-flow steps)
