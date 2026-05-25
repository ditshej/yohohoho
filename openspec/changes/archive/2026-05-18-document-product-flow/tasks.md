## Tasks

### brainstorm skill

- [ ] Update `.claude/skills/brainstorm/SKILL.md`: add file-write step — skill writes `openspec/product/<topic>/briefing.md` via Write tool after producing the output inline
- [ ] Update `.claude/skills/brainstorm/SKILL.md`: update bridge prompt to reference `/grill-me` and `openspec/ROADMAP.md`
- [ ] Add `model: opus` to `.claude/commands/brainstorm.md` YAML frontmatter

### grill-me skill

- [ ] Update `.claude/skills/grill-me/SKILL.md`: add read step — if `openspec/product/<topic>/briefing.md` exists, read it as starting context
- [ ] Update `.claude/skills/grill-me/SKILL.md`: add file-write step — skill writes `openspec/product/<topic>/aligned.md` via Write tool after producing the output inline
- [ ] Add `model: opus` to `.claude/commands/grill-me.md` YAML frontmatter

### recap + explore commands

- [ ] Add `model: opus` to `.claude/commands/recap.md` YAML frontmatter
- [ ] Add `model: opus` to `.claude/commands/opsx/explore.md` YAML frontmatter (vendor-managed: document as known patch in dev-setup.md)

### openspec-flow guideline

- [ ] Expand Product Level section in `.ai/guidelines/openspec-flow.md`: add storage convention (`openspec/product/<topic>/`), context-reset note (`/clear` between phases), and ROADMAP.md format template
- [ ] Add "Autonomous Mode (AGENT_MISSION)" section to `.ai/guidelines/openspec-flow.md`
- [ ] Run `php artisan boost:install --guidelines` to regenerate `CLAUDE.md`

### AGENT_MISSION template

- [ ] Rewrite `openspec/AGENT_MISSION.md.example` to < 30 lines: pointer to ROADMAP.md, mode setting, mandatory stop format only — remove per-change flow steps

### docs/dev-setup.md

- [ ] Add "Known Post-Update Patches" note in `docs/dev-setup.md` §3 documenting that `opsx/explore.md` must have `model: opus` re-applied after `npx openspec update`

### specs

- [ ] Update Purpose section in `openspec/specs/brainstorm-skill/spec.md`
- [ ] Update Purpose section in `openspec/specs/grill-me-skill/spec.md`
