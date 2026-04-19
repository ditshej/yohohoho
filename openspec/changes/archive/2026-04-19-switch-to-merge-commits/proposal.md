## Why

The project currently uses "Rebase and merge" as the sole merge strategy. This produces a linear history but hides the boundaries of OpenSpec changes behind commit-scope conventions. Switching to merge commits (`--no-ff`) makes each OpenSpec change a single, explicit merge-commit node — matching the mental model of "one change = one unit" — and removes the mandatory pre-merge rebase step (no more force-pushes, no more conflict-rework on already-reviewed branches).

## What Changes

- **BREAKING (workflow only):** Merge strategy on `main` changes from rebase-merge to merge-commit (`--no-ff`).
- Update `docs/dev-setup.md` in all locations that describe the merge flow, history example, and branch protection ruleset (including a new sub-section on bisect with `--first-parent`).
- Update `CONTRIBUTING.md` and `CLAUDE.md` to mirror the new flow.
- Replace `gh pr merge --rebase --delete-branch` with `gh pr merge --merge --delete-branch` everywhere.
- Introduce feature-branch hygiene convention before PR: `git commit --fixup=<sha>` during work + `git rebase -i --autosquash origin/main` before push (with recommendation to set `rebase.autosquash = true` globally).
- Add a GitHub settings checklist (manual steps outside git) covering Repo → Pull Requests toggles, Ruleset `main-protection` allowed merge methods, default merge commit message = PR title, and verification that "Require linear history" stays OFF.
- Add a verification step to tasks.md: after first merge-commit lands, run `git cliff -o CHANGELOG.md` once to confirm merge commits are filtered out.

## Capabilities

### New Capabilities
- `git-workflow`: Formalizes the project's git branching, merging, and feature-branch-hygiene conventions as explicit, testable requirements. This change is the first entry and establishes the merge-strategy requirement. Future workflow changes (e.g., squash policy, commit-message linting) would modify this same capability.

### Modified Capabilities
<!-- None. -->

## Non-goals

- No code changes in the application (no PHP, no tests, no migrations).
- No changes to other existing capabilities (api-import, card-browsing, card-import, ci-pipeline are untouched).
- No migration of existing PRs — none are open at time of writing.
- No changes to other projects — the user will apply the same pattern manually to their other two repositories and to any future boilerplate derived from this project.
- No changes to `cliff.toml` — the existing `filter_unconventional = true` already excludes merge commits; verified post-merge, not pre-emptively.

## Impact

- **Docs:** `docs/dev-setup.md` (multiple sections: feature-branch convention, workflow per change, resulting-history example, CLAUDE.md template block, branch-protection ruleset), `CONTRIBUTING.md`, `CLAUDE.md`.
- **External configuration (manual, not committable):** GitHub repository settings and the `main-protection` ruleset.
- **Developer workflow:** New `--fixup` / `--autosquash` convention before pushing a feature branch. No rebase onto `origin/main` required before merging.
- **Tooling:** None broken. `git-cliff` continues to work unchanged (verified after first merge commit). `git bisect` on `main` should use `--first-parent` going forward — documented.
- **No runtime impact.** Application behavior, CI, tests, and dependencies are untouched.
