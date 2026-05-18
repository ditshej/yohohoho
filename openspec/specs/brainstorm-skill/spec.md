# brainstorm-skill Specification

## Purpose
TBD - created by archiving change brainstorm-align-recap-framework. Update Purpose after archive.
## Requirements
### Requirement: Brainstorm Skill
The system SHALL provide a `/brainstorm` slash command and Claude Code skill that facilitates open-ended product ideation before any OpenSpec change is started.

#### Scenario: Start brainstorm with a topic
- **WHEN** user runs `/brainstorm` with an optional topic or description
- **THEN** AI facilitates divergent thinking using open-ended questions, explores multiple angles, and helps the user articulate what they want to build

#### Scenario: Output product briefing document
- **WHEN** the brainstorm session reaches a natural conclusion
- **THEN** AI produces a product briefing MD file (stored as `docs/brainstorm-<topic>.md` or shown inline) summarizing: the problem, the vision, the constraints, and a rough list of capabilities to build

#### Scenario: Bridge to OpenSpec
- **WHEN** the brainstorm output is ready
- **THEN** AI suggests the next step: "Use this briefing to create a ROADMAP, then start `/opsx:new` for each change"

