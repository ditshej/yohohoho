# grill-me-skill Specification

## Purpose
TBD - created by archiving change brainstorm-align-recap-framework. Update Purpose after archive.
## Requirements
### Requirement: Grill-me Skill
The system SHALL provide a `/grill-me` slash command and Claude Code skill for the Align phase. The AI MUST interview the developer one question at a time to achieve shared understanding of a plan or design.

#### Scenario: Interview with a plan as context
- **WHEN** user runs `/grill-me` with a description or after sharing a proposal/design
- **THEN** AI asks one probing question at a time, provides its own recommended answer for each question, and walks down the full decision tree

#### Scenario: Codebase-answerable questions
- **WHEN** a question can be answered by reading existing code
- **THEN** AI reads the relevant code and answers itself rather than asking the developer

#### Scenario: Reach shared understanding
- **WHEN** all major branches of the design tree have been resolved
- **THEN** AI produces a shared-understanding summary MD documenting the agreed decisions

#### Scenario: Bridge to OpenSpec
- **WHEN** the grill-me session is complete
- **THEN** AI suggests: "Ready to create the change. Run `/opsx:new <name>` to start."

