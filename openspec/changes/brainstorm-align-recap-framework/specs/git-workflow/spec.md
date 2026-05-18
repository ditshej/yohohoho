## MODIFIED Requirements

### Requirement: OpenSpec Flow Documentation
The `.ai/guidelines/openspec-flow.md` documentation is extended with a product-level section and two new change-level steps (recap and refactor).

#### Scenario: Product-level section added
- **WHEN** reading the flow documentation
- **THEN** a "Product Level" section appears before the change-level workflow, describing: Brainstorm → Align (grill-me) → Roadmap (vertical slicing)

#### Scenario: Recap step visible in change workflow
- **WHEN** reading the step-by-step change workflow
- **THEN** a "Recap" step appears between "AI Review" and "/opsx:sync"

#### Scenario: Refactor step visible in change workflow
- **WHEN** reading the step-by-step change workflow
- **THEN** a "Refactor" step appears between "Recap" and "/opsx:sync", with the three-question checklist and a reference to the `simplify` skill

#### Scenario: AGENT_MISSION section updated in dev-setup
- **WHEN** reading dev-setup.md §17-18
- **THEN** the TBD stubs are replaced with a complete ROADMAP.md template and AGENT_MISSION.md template reflecting the new flow
