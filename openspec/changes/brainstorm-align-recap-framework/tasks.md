## Tasks

### brainstorm skill

- [ ] Create `.claude/skills/brainstorm/SKILL.md` with YAML frontmatter and skill instructions (attribution to benithors/skills)
- [ ] Create `.claude/commands/brainstorm.md` as slash command wrapper

### grill-me skill

- [ ] Create `.claude/skills/grill-me/SKILL.md` based on mattpocock's version, extended with output format and bridge prompt (attribution to mattpocock/skills and benithors/skills)
- [ ] Create `.claude/commands/grill-me.md` as slash command wrapper

### recap skill

- [ ] Create `.claude/skills/recap/SKILL.md` with fixed three-section output: "How does this work?", "What is the flow?", "Diagram" (Mermaid required)
- [ ] Create `.claude/commands/recap.md` as slash command wrapper

### flow documentation

- [ ] Extend `.ai/guidelines/openspec-flow.md` with product-level section (Brainstorm → Align → Roadmap), recap step, and refactor step (with three-question checklist referencing `simplify`)
- [ ] Run `php artisan boost:install --guidelines` to sync CLAUDE.md
- [ ] Update `docs/dev-setup.md` §17 with ROADMAP.md template
- [ ] Update `docs/dev-setup.md` §18 with AGENT_MISSION.md template reflecting new flow
- [ ] Update `docs/dev-setup.md` §7 to mention recap and refactor steps
- [ ] Add `Bash(npx @fission-ai/openspec*)` to permissions baseline in `docs/dev-setup.md` §11 (covers `npx @fission-ai/openspec init`)
- [ ] Create `openspec/AGENT_MISSION.md.example` as copy-paste template
