## MODIFIED Requirements

### Requirement: Brainstorm Skill
The system SHALL provide a `/brainstorm` slash command and Claude Code skill that facilitates open-ended product ideation before any OpenSpec change is started.

#### Scenario: Start brainstorm with a topic
- **WHEN** user runs `/brainstorm` with an optional topic or description
- **THEN** AI facilitates divergent thinking using open-ended questions, explores multiple angles, and helps the user articulate what they want to build

#### Scenario: Output product briefing document
- **WHEN** the brainstorm session reaches a natural conclusion
- **THEN** AI writes the product briefing to `openspec/product/<topic>/briefing.md` AND shows it inline, containing: the problem, the vision, the constraints, a rough list of capabilities, and open questions

#### Scenario: Bridge to OpenSpec
- **WHEN** the brainstorm output is ready
- **THEN** AI suggests: "Run `/grill-me` to align on decisions, or create `openspec/ROADMAP.md` with these capabilities and start `/opsx:new <name>`"

## ADDED Requirements

### Requirement: Brainstorm Skill uses Opus model
The `/brainstorm` slash command SHALL be pinned to the Opus model via `model: opus` in its YAML frontmatter, ensuring maximum reasoning quality for product ideation.

#### Scenario: Model is pinned in command frontmatter
- **WHEN** the `.claude/commands/brainstorm.md` file is inspected
- **THEN** the YAML frontmatter contains `model: opus`
