## ADDED Requirements

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
