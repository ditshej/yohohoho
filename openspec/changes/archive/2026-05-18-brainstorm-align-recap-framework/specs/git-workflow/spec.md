## ADDED Requirements

### Requirement: OpenSpec Flow Documentation Includes Product Level
The git-workflow documentation SHALL describe a product-level phase (Brainstorm → Align → Roadmap) that precedes the change-level OpenSpec workflow.

#### Scenario: Product level section visible
- **WHEN** a developer reads the openspec-flow.md guideline
- **THEN** a "Product Level" section appears before the change-level workflow steps
- **AND** it references /brainstorm, /grill-me, and a manual roadmap step

### Requirement: OpenSpec Flow Documentation Includes Recap Step
The git-workflow documentation SHALL describe a Recap step between AI Review and spec sync.

#### Scenario: Recap step in workflow
- **WHEN** a developer reads the change-level workflow steps
- **THEN** a "Recap" step appears between AI Review and /opsx:sync
- **AND** it references the /recap command

### Requirement: OpenSpec Flow Documentation Includes Refactor Step
The git-workflow documentation SHALL describe a Refactor step between Recap and spec sync, with a three-question checklist.

#### Scenario: Refactor step in workflow
- **WHEN** a developer reads the change-level workflow steps
- **THEN** a "Refactor" step appears between Recap and /opsx:sync
- **AND** it includes the three-question checklist referencing the simplify skill
