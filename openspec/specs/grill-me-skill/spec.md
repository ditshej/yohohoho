# grill-me-skill Specification

## Purpose

Defines the `/grill-me` slash command and skill for the Align phase. The skill interviews the developer one question at a time to reach shared understanding of a plan or design. It reads `openspec/product/<topic>/briefing.md` if present and persists the session output to `openspec/product/<topic>/aligned.md`.
## Requirements
### Requirement: Grill-me Skill
The system SHALL provide a `/grill-me` slash command and Claude Code skill for the Align phase. The AI MUST interview the developer one question at a time to achieve shared understanding of a plan or design.

#### Scenario: Interview with a plan as context
- **WHEN** user runs `/grill-me` with a description or after sharing a proposal/design
- **THEN** AI asks one probing question at a time, provides its own recommended answer for each question, and walks down the full decision tree

#### Scenario: Read briefing if present
- **WHEN** user runs `/grill-me` with a topic name and `openspec/product/<topic>/briefing.md` exists
- **THEN** AI reads the briefing document and uses it as the starting context for the interview without asking the user to re-describe the problem

#### Scenario: Codebase-answerable questions
- **WHEN** a question can be answered by reading existing code
- **THEN** AI reads the relevant code and answers itself rather than asking the developer

#### Scenario: Reach shared understanding
- **WHEN** all major branches of the design tree have been resolved
- **THEN** AI writes the shared-understanding summary to `openspec/product/<topic>/aligned.md` AND shows it inline, containing: agreed decisions, resolved questions, and remaining open questions

#### Scenario: Bridge to OpenSpec
- **WHEN** the grill-me session is complete
- **THEN** AI suggests: "Ready to create the change. Run `/opsx:new <name>` to start."

### Requirement: Grill-me Skill uses Opus model
The `/grill-me` slash command SHALL be pinned to the Opus model via `model: opus` in its YAML frontmatter.

#### Scenario: Model is pinned in command frontmatter
- **WHEN** the `.claude/commands/grill-me.md` file is inspected
- **THEN** the YAML frontmatter contains `model: opus`

