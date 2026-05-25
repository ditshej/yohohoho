## Purpose

Defines the git workflow conventions for this project: merge strategy on `main`, commit message format, branch hygiene, and the OpenSpec feature branch flow including the full change lifecycle (Brainstorm → Align → Propose → Implement → Verify → Review → Recap → Refactor → Sync → Archive → PR → Merge).
## Requirements
### Requirement: Merge strategy on `main`

The project SHALL use merge commits (`--no-ff`) as the sole merge strategy for pull requests into `main`. Rebase-merge and squash-merge SHALL NOT be used.

#### Scenario: PR is merged via GitHub UI

- **WHEN** a pull request is merged into `main` via the GitHub web UI
- **THEN** the selected merge method is "Create a merge commit"
- **AND** a merge commit is added to `main` with exactly two parents (the previous `main` tip and the feature branch tip)

#### Scenario: PR is merged via `gh` CLI

- **WHEN** a pull request is merged using `gh pr merge`
- **THEN** the command uses the `--merge` flag (not `--rebase` or `--squash`)

#### Scenario: Ruleset prevents non-merge-commit methods

- **WHEN** a contributor attempts to rebase-merge or squash-merge a PR into `main`
- **THEN** the `main-protection` ruleset rejects the merge
- **AND** only "Merge commits" is listed under Allowed merge methods

### Requirement: Merge commit message format

The merge commit subject line SHALL be the pull request title, which in turn SHALL follow the Conventional Commits format used elsewhere in the project (`<type>(<change-name>): <description>`).

#### Scenario: Default merge commit message

- **WHEN** a PR is merged into `main`
- **THEN** the resulting merge commit's subject line equals the PR title
- **AND** the PR title starts with a conventional-commit type (`feat`, `fix`, `docs`, `refactor`, etc.)

### Requirement: Feature branch hygiene before push

Before pushing a feature branch for PR, the contributor SHALL clean up intermediate work-in-progress commits so that only meaningful commits land on `main` via the merge commit's second-parent lineage.

#### Scenario: Fixup commits are created during work

- **WHEN** a contributor makes a small correction to a previous commit on the feature branch
- **THEN** the correction is committed with `git commit --fixup=<target-sha>`
- **AND** the resulting commit message starts with `fixup! `

#### Scenario: Autosquash before push

- **WHEN** a contributor is ready to push the feature branch for PR review
- **THEN** they run `git rebase -i --autosquash origin/main` (or equivalent, enabled by default via `rebase.autosquash = true`)
- **AND** all `fixup!` commits are collapsed into their target commits before the push

### Requirement: Bisecting on `main`

`git bisect` on `main` SHALL be performed using `--first-parent` so that bisection steps align with OpenSpec change boundaries (one merge commit per change).

#### Scenario: Documented bisect usage

- **WHEN** a contributor consults the project documentation for bisect guidance
- **THEN** the documentation states the recommended command is `git bisect start --first-parent` or equivalently sets `bisect.firstParent = true` as a global git config

### Requirement: Linear history NOT required

The `main-protection` ruleset SHALL NOT enforce "Require linear history", since merge commits are incompatible with that setting.

#### Scenario: Ruleset inspection

- **WHEN** the `main-protection` ruleset is reviewed
- **THEN** "Require linear history" is disabled

### Requirement: Changelog fidelity under merge commits

The `git-cliff` changelog output SHALL NOT include merge commits as changelog entries.

#### Scenario: First post-switch changelog run

- **WHEN** the first merge commit has landed on `main` under the new strategy and `git cliff -o CHANGELOG.md` is run
- **THEN** the output contains entries for the inner feature/fix commits of the merged branch
- **AND** the output does NOT contain an entry corresponding to the merge commit itself

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

