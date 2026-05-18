## Context

The OpenSpec flow is now well-established for the change level, but lacks product-level scaffolding and post-review quality steps. Three new skills fill the gaps:

- **`brainstorm`** (product level, before `/opsx:new`) — divergent ideation
- **`grill-me`** (product/change level) — convergent alignment via Q&A
- **`recap`** (change level, after AI review) — structured feature explanation

The refactor step reuses the existing `simplify` skill with a three-question checklist in the documentation.

## Goals / Non-Goals

**Goals:**
- Three new skills + slash commands installed in `.claude/skills/` and `.claude/commands/`
- Each skill has a fixed, predictable output format
- Flow documentation (`openspec-flow.md`) reflects the complete pipeline including product-level phase
- `dev-setup.md` §17-18 completed with ROADMAP + AGENT_MISSION templates

**Non-Goals:**
- Vertical slicing skill (roadmap decomposition)
- Automated CI/testing for skill behavior
- Modifying OpenSpec-owned skills

## Decisions

**Skill delivery:** Both `.claude/skills/<name>/SKILL.md` (auto-triggered by Skill tool) and `.claude/commands/<name>.md` (slash command). Consistent with existing OpenSpec pattern (`delivery: both`).

**brainstorm vs. benithors original:** Adapt rather than copy verbatim. The benithors skill is a heavy framework (40+ techniques). For this project, a lighter version focused on: open-ended exploration → summary MD → bridge to OpenSpec. Attribution note in SKILL.md header.

**grill-me:** Use mattpocock's minimal version as the base (proven, concise). Add: output format (shared-understanding MD), bridge to `/opsx:new`, codebase-exploration guidance.

**recap output format:** Three fixed sections — "How does this work?", "What is the flow?", "Diagram" (Mermaid always required). No flexibility — the whole point is that the developer cannot skip the diagram.

**refactor:** No new skill. A checklist in `openspec-flow.md` is enough: three questions + call `simplify` or `laravel-simplifier`. Adding a dedicated skill would duplicate `simplify`'s purpose.

**AGENT_MISSION template:** Store as `openspec/AGENT_MISSION.md.example` to signal it's a template, not active configuration. Projects copy and customize it.

## Risks / Trade-offs

- **brainstorm scope creep:** The session has no natural end. Mitigated by: output format (produces a document → signal to stop) and bridge prompt.
- **recap on tiny changes:** A Mermaid diagram for a one-liner change feels heavy. Accepted trade-off — the "penetrant" enforcement is the point.
- **grill-me question depth:** AI may ask trivial questions. Mitigated by: "If codebase-answerable, explore instead of asking."
