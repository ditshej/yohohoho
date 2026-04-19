## 1. Documentation edits — `docs/dev-setup.md`

- [x] 1.1 Replace "No squash merges — full history stays on `main`" statements (lines ~151, ~483, ~663) with merge-commit wording.
- [x] 1.2 Update Workflow per Change block (~lines 185–189 and 528–536): remove pre-merge `git rebase origin/main`; change `gh pr merge --rebase --delete-branch` to `gh pr merge --merge --delete-branch`.
- [x] 1.3 Rewrite the "Resulting History on main" example (~lines 541–551) to show a merge-commit graph (ASCII with `*`, `|\`, `|/`) instead of a flat list.
- [x] 1.4 Update the Branch Protection Ruleset section (~line 1041): change "Allowed merge methods: Rebase only" to "Merge commits only" and add explicit note that "Require linear history" must stay OFF.
- [x] 1.5 Add a new sub-section "Feature branch hygiene" covering `git commit --fixup=<sha>` + `git rebase -i --autosquash origin/main` and the `git config --global rebase.autosquash true` recommendation.
- [x] 1.6 Add a new sub-section "Bisecting on `main`" explaining `git bisect start --first-parent` and the `git config --global bisect.firstParent true` recommendation.

## 2. Documentation edits — `CONTRIBUTING.md` and `CLAUDE.md`

- [x] 2.1 `CONTRIBUTING.md` (lines 23, 47–54): mirror the new flow — remove the rebase step, switch `gh pr merge` flag, update wording about history.
- [x] 2.2 `CLAUDE.md` (lines 196, 240–249): mirror the new flow in the OpenSpec Feature Branch Flow section.

## 3. Verification & tooling

- [x] 3.1 Run `vendor/bin/pint --dirty --format agent` (no-op expected; only docs changed) to confirm no style drift.
- [x] 3.2 Open PR from `feat/switch-to-merge-commits` into `main`. This PR is merged under the OLD rebase-merge rules (transition commit).

## 4. GitHub configuration (manual, post-merge of this PR)

- [ ] 4.1 Repo Settings → General → Pull Requests: enable "Allow merge commits"; disable "Allow rebase merging" and "Allow squash merging".
- [ ] 4.2 Repo Settings → General → Pull Requests: set default merge commit message to "Pull request title".
- [ ] 4.3 Settings → Rules → Rulesets → `main-protection` → Allowed merge methods: change to "Merge commits" only.
- [ ] 4.4 Verify "Require linear history" is OFF in the `main-protection` ruleset.
- [ ] 4.5 Verify "Automatically delete head branches" remains enabled in Settings → General.

## 5. Post-switch verification (after the first merge-commit PR lands)

- [ ] 5.1 After the first post-switch PR is merged, inspect `git log --oneline --graph -n 10 main` to confirm a merge commit with two parents appears.
- [ ] 5.2 Run `git cliff -o CHANGELOG.md` and confirm no merge-commit entries appear; if they do, apply the `cliff.toml` fallback described in design.md (Risks section).
- [ ] 5.3 Run `git bisect start --first-parent <bad> <good>` on a harmless range to confirm bisect walks only merge nodes. Reset with `git bisect reset`.
