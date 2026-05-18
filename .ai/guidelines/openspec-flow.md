## Git + OpenSpec Feature Branch Flow

> **Note:** Product-level phases (Brainstorm, Align, Roadmap) and post-review steps (Recap, Refactor) will be added in a future change — TBD.

**Rule:** Every new feature or major change ALWAYS starts in the OpenSpec flow on its own `feat/<change-name>` branch — never implement directly, not even in plan mode.

Every OpenSpec change gets its own feature branch and lands on `main` as a single merge commit (`--no-ff`). No squash, no rebase-merge. No direct push to `main` — always via PR with CI passing.

### CLI Note

OpenSpec is installed as a local dev-dependency only (`@fission-ai/openspec` in `package.json`). Whenever a skill or command shows `openspec ...`, always run it as `npx openspec ...`. Never assume a global installation.

### Branch Naming Convention

```
feat/<change-name>      # e.g. feat/card-management
```

### Workflow Commands

| Command | When to use | What it does |
|---|---|---|
| `/opsx:explore` | Optional first step, or to investigate options mid-change | Researches tradeoffs, asks clarifying questions. Does not create artifacts or code |
| `/opsx:new` | Starting a new change — recommended first step after `git checkout` | Creates `openspec/changes/<name>/` skeleton, shows first artifact template |
| `/opsx:continue` | After `new`, iteratively creating artifacts | Creates the next pending artifact (one per invocation). Loop until `isComplete: true` |
| `/opsx:ff` | After `new`, when scope is clear and you want all artifacts at once | Fast-forwards through all artifacts in one go |
| `/opsx:propose` | Express shortcut — creates skeleton AND all artifacts in one step | Equivalent to `new` + `ff` combined |
| `/opsx:apply` | After all artifacts are done (doc commit made) | Works through tasks in `tasks.md` one by one (TDD) |
| `/opsx:verify` | After implementation | Checks Completeness, Correctness, Coherence against specs. Reports CRITICAL/WARNING/SUGGESTION |
| `/opsx:sync` | After AI Review, before archive | Merges delta specs (`changes/<n>/specs/`) into main specs (`specs/<cap>/spec.md`) |
| `/opsx:archive` | After sync | Moves change dir to `openspec/changes/archive/YYYY-MM-DD-<name>/`. Contains fallback sync prompt |
| `/opsx:bulk-archive` | When closing multiple parallel changes at once | Archives several changes in one go |
| `/opsx:onboard` | First time using OpenSpec, or onboarding a new team member | Guided walkthrough of a full workflow cycle with narration |

### Recommended Workflow (Iterative — Standard)

```bash
# 0. Explore (optional)
# /opsx:explore — investigate ideas and tradeoffs for this change
# → CHECKPOINT: Present findings → wait for OK before continuing

# 1. Create branch
git checkout -b feat/<change-name>

# 2. Create change skeleton
# /opsx:new <change-name>
# → shows first artifact template, does not fill it yet

# 3. Build artifacts iteratively
# /opsx:continue      # creates proposal.md
# /opsx:continue      # creates specs/<capability>/spec.md
# /opsx:continue      # creates design.md
# /opsx:continue      # creates tasks.md (isComplete: true)
# → CHECKPOINT: Present proposal summary → wait for OK before implementing
git add openspec/ && git commit -m "docs(<change-name>): add proposal, design and tasks"

# 4. Implementation (TDD — tests first)
# /opsx:apply — work through tasks one by one
# → Commit(s): "feat(<change-name>): ...", "test(<change-name>): ...", etc.

# 5. Verify
# /opsx:verify — checks Completeness, Correctness, Coherence against specs
# → Fix all CRITICALs before proceeding

# 6. AI Review
# laravel-simplifier Agent — automated review (spawn parallel subagents)
# → Fix critical findings, commit: "refactor(<change-name>): apply review feedback"
# → CHECKPOINT: Present change summary:
#     - What changed (architecture, new/modified files)
#     - Test results (N passed)
#     - How to review manually (git diff, which pages/endpoints to test)
#   → Wait for user OK before proceeding

# 7. Sync specs
# /opsx:sync — merges delta specs into main specs
git add openspec/ && git commit -m "docs(<change-name>): sync specs"

# 8. Archive
# /opsx:archive — mv → openspec/changes/archive/YYYY-MM-DD-<change-name>/
git add openspec/ && git commit -m "docs(<change-name>): archive change"

# 9. Clean up fixup commits and push
git fetch origin && git rebase -i --autosquash origin/main   # collapses `fixup!` commits; no-op otherwise
git push -u origin feat/<change-name>
gh pr create --title "feat(<change-name>): <description>"
# → CI must pass (tests + lint), then merge via GitHub ("Create a merge commit")

# 10. Merge and cleanup
gh pr merge --merge --delete-branch
git checkout main && git pull && git remote prune origin
```

### Express Alternatives

Use these when the scope is clear and you don't need the step-by-step artifact flow:

```bash
# Option A: propose (skeleton + all artifacts in one step)
/opsx:propose <change-name>

# Option B: new + ff (same result in two commands)
/opsx:new <change-name>
/opsx:ff
```

Both lead to the same doc commit. Then continue with step 4 (apply) above.

### Resulting History on main

```
*   Merge pull request #43 from feat/list-packs
|\
| * docs(list-packs): archive change
| * docs(list-packs): sync specs
| * refactor(list-packs): apply review feedback
| * feat(list-packs): add packs() and pack() endpoints
| * docs(list-packs): add proposal, design and tasks
|/
*   Merge pull request #42 from feat/prev-change
```

Use `git log --first-parent main` to see only the merge commits (one per change).

Each feature follows: Explore → New → Continue → Implement → Verify → Review → Sync → Archive → PR → Merge.
Use the change name as commit scope for every commit on that branch.
Multiple commits per phase are fine — commit as often as makes sense (feat, fix, test, refactor, etc.).
