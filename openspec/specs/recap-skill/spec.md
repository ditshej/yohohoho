# recap-skill Specification

## Purpose
TBD - created by archiving change brainstorm-align-recap-framework. Update Purpose after archive.
## Requirements
### Requirement: Recap Skill
The system SHALL provide a `/recap` slash command and Claude Code skill for the post-review step. After AI review and before sync/archive, the AI MUST explain what was built in a fixed three-section format.

#### Scenario: Generate recap for the current change
- **WHEN** user runs `/recap` (optionally with a change name)
- **THEN** AI reads the implemented code, proposal, and design artifacts, then produces a three-section markdown response

#### Scenario: Fixed output format
- **WHEN** the recap is generated
- **THEN** it always contains exactly three sections:
  1. **How does this work?** — a plain-language explanation of the feature
  2. **What is the flow?** — step-by-step description of the user/data flow
  3. **Diagram** — a Mermaid diagram visualizing the flow (always required)

#### Scenario: No Mermaid diagram — invalid recap
- **WHEN** the generated recap is missing the Mermaid diagram section
- **THEN** AI must add it before considering the recap complete

#### Scenario: Bridge to next step
- **WHEN** the recap is complete
- **THEN** AI reminds: "Review the diagram and explanation, then run `/opsx:sync` when ready."

