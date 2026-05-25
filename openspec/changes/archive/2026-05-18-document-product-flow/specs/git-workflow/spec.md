## ADDED Requirements

### Requirement: Product-Level Artifacts Stored in openspec/product/
The project SHALL store product-level phase artifacts in `openspec/product/<topic>/`, committed to the repository, so they persist across `/clear` and session boundaries.

#### Scenario: Brainstorm output persisted
- **WHEN** a developer completes a `/brainstorm` session for topic `<topic>`
- **THEN** the product briefing is written to `openspec/product/<topic>/briefing.md`
- **AND** the file is committed to version control alongside the code

#### Scenario: Align output persisted
- **WHEN** a developer completes a `/grill-me` session for topic `<topic>`
- **THEN** the shared-understanding summary is written to `openspec/product/<topic>/aligned.md`
- **AND** the file is committed to version control alongside the code

### Requirement: Context Reset Between Product-Level Phases
The workflow documentation SHALL specify that developers MUST run `/clear` between product-level phases (Brainstorm → Align → Roadmap) to prevent context pollution, relying on persisted files for continuity.

#### Scenario: Context reset documented
- **WHEN** a developer reads the openspec-flow.md guideline product-level section
- **THEN** it explicitly states to run `/clear` between phases
- **AND** it explains that skills read previous artifacts from `openspec/product/` files

### Requirement: Autonomous Mode Documented via AGENT_MISSION
The workflow documentation SHALL describe the optional `openspec/AGENT_MISSION.md` file and instruct Claude to read it at session start if present.

#### Scenario: AGENT_MISSION referenced in flow docs
- **WHEN** a developer reads the openspec-flow.md guideline
- **THEN** an "Autonomous Mode" section explains that Claude reads `openspec/AGENT_MISSION.md` at session start when the file exists
- **AND** it references `openspec/AGENT_MISSION.md.example` as a copy-paste template

### Requirement: Thinking Commands Pinned to Opus Model
The workflow documentation SHALL specify which Claude Code slash commands use `model: opus` and which use the session default, based on reasoning intensity.

#### Scenario: Thinking commands use Opus
- **WHEN** a developer runs `/brainstorm`, `/grill-me`, `/recap`, or `/opsx:explore`
- **THEN** the command runs with Opus 4.7 as the active model (set via `model: opus` in command frontmatter)

#### Scenario: Implementation commands use session default
- **WHEN** a developer runs `/opsx:apply`, `/opsx:verify`, `/opsx:sync`, `/opsx:archive`, or other implementation commands
- **THEN** the command runs with the session default model (Sonnet)
