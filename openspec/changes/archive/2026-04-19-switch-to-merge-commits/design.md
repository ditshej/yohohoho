## Context

The repository uses "Rebase and merge" as the sole merge strategy, enforced by the `main-protection` GitHub ruleset. This produces a linear history on `main` where each OpenSpec change contributes 2–5 flat commits, distinguishable only by their `<change-name>` commit scope. The boundary between changes is implicit.

OpenSpec's mental model treats a change as an atomic unit (proposal → design → specs → tasks → apply → archive). Merge commits (`--no-ff`) make that unit explicit as a single node in the graph. The branch's internal commits (including `--fixup` cleanup) remain visible via `git log` but do not pollute the first-parent history on `main`.

No application code is affected. The change is entirely documentation plus GitHub repository configuration.

## Goals / Non-Goals

**Goals:**
- Make each OpenSpec change appear as one merge-commit node on `main`.
- Remove the mandatory pre-merge `git rebase origin/main` step (and the accompanying force-push implication).
- Keep `git-cliff` output unchanged (merge commits are non-conventional and already filtered).
- Keep `git bisect` usable on `main` via `--first-parent`.
- Establish a feature-branch hygiene convention that keeps inner-branch commits meaningful (fixup/autosquash) without requiring a full interactive rebase.

**Non-Goals:**
- No application code changes.
- No OpenSpec spec requirements changes.
- No automation of the GitHub settings (the ruleset cannot be committed; it is a manual, one-time configuration step).
- No migration of historical commits — the switch applies to future merges only.
- No `cliff.toml` changes (verified sufficient by `filter_unconventional = true`).

## Decisions

### 1. Merge method: merge commit (`--no-ff`), not squash

**Rationale:** A single OpenSpec change typically consists of multiple meaningful commits (`docs(x): propose`, `feat(x): ...`, `test(x): ...`, `refactor(x): apply review feedback`, `docs(x): archive`). Squash would destroy that structure and defeat `git-cliff`, which relies on conventional-commit granularity to generate the changelog. `--no-ff` preserves every commit and adds one merge node on top.

**Alternatives considered:**
- *Squash merge:* Rejected — destroys changelog fidelity and makes `git blame`-on-`main` less useful.
- *Fast-forward (ff-only):* Equivalent to current rebase-merge; rejected for the same visibility reason that motivated this proposal.

### 2. Merge commit message: PR title, not default `Merge pull request #N`

**Rationale:** GitHub's default `Merge pull request #N from feat/<name>` is noisy and non-conventional. Using the PR title (which itself follows Conventional Commits as `feat(<change-name>): <description>`) keeps the merge-commit subject line aligned with the inner commits. `git-cliff` will still filter it because the merge commit has two parents and is detected as a merge — but if it ever falls through, the conventional-commit form means it lands in a sensible group rather than as noise.

**Alternatives considered:**
- *GitHub default:* Rejected — see above.
- *Custom template with `#N`:* Too fragile across tooling.

### 3. Feature-branch hygiene: `--fixup` + `--autosquash`

**Rationale:** With merge commits, every inner-branch commit appears on `main` (via second-parent). Ad-hoc "wip", "fix typo" commits would leak into the permanent history. Option 2 (`git commit --fixup=<sha>` during work, `git rebase -i --autosquash origin/main` before push) keeps the convention lightweight: developers mark fixups explicitly at commit time, and the final rebase reorders and squashes them automatically with minimal editor interaction. The global `rebase.autosquash = true` setting makes `--autosquash` implicit.

**Alternatives considered:**
- *Manual `rebase -i`:* More error-prone, higher cognitive load each time.
- *Laissez-faire (no cleanup):* Rejected — leaks WIP commits into the permanent record.

### 4. `bisect.firstParent = true` recommendation

**Rationale:** With merge commits, a naive `git bisect` traverses both parents and may land on an intermediate feature-branch commit (possibly with failing tests). `--first-parent` restricts bisection to the main-line merge nodes — one per OpenSpec change — making each bisect step a meaningful binary choice at the change level. Recommended as a global git config in `dev-setup.md`.

### 5. `cliff.toml` remains unchanged

**Rationale:** The existing configuration has `filter_unconventional = true`. Merge-commit messages of the form `feat(<change-name>): <description>` (decision 2) are conventional and would be picked up — but `git-cliff` also honors commit-parent-count detection via `commit_parsers` and treats merges as non-narrative by default. Rather than speculating, the tasks.md includes an explicit post-first-merge verification step (`git cliff -o CHANGELOG.md`) and a fallback plan if the output changes.

## Risks / Trade-offs

- **[Risk] `git-cliff` picks up merge commits as changelog entries** → **Mitigation:** Task 5 verifies the output after the first merge-commit. If duplicates appear, add a `commit_parsers` rule with `message = "^Merge pull request"` or filter by `parents >= 2` (git-cliff supports parent filtering).
- **[Risk] Developers forget `--fixup` and leak WIP commits to `main`** → **Mitigation:** Documented in `dev-setup.md` with the command inline; global `rebase.autosquash = true` also documented; reviewable in the PR diff before merging.
- **[Risk] GitHub ruleset mis-configured (e.g., "Require linear history" left ON)** → **Mitigation:** Explicit verification step in the GitHub-settings checklist; linear-history toggle is called out by name.
- **[Trade-off] Graph becomes 2D** — tools like `git log --oneline` show both main-line and branch commits interleaved. Users need `--first-parent` or `--graph` for clarity. Accepted; documented.
- **[Trade-off] No automation for ruleset** — GitHub rulesets can be managed via API/Terraform but adding that tooling here is out of scope.

## Migration Plan

The change is additive and low-risk (docs + one-time GitHub config toggle). Rollback is trivial (revert the docs PR, flip the ruleset back).

1. **Docs PR** on `feat/switch-to-merge-commits` — merged with the *old* rebase-flow (final rebase-merge). This is the transition commit.
2. **Immediately after merge:** apply the GitHub-settings checklist (see tasks.md). This must happen before the next PR is opened to avoid confusion between documented flow and enforced flow.
3. **First new feature branch after switch:** normal flow per new docs. After its merge, run `git cliff -o CHANGELOG.md` and compare against the previous changelog; if merge commits appear as entries, apply the cliff.toml fallback from "Risks".

No rollback complexity: reverting the ruleset and the docs PR restores the prior state.

## Open Questions

- None. All previously open questions (merge-commit message format, bisect recommendation, cliff verification, OpenSpec scope retention) were resolved during the explore session and encoded in the decisions above.
