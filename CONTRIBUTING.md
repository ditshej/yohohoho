# Contributing

Contributions are welcome — bug reports, improvements, and new features.

## Development Workflow

This project uses [OpenSpec](https://github.com/fission-ai/openspec) for structured change management. Every new feature or significant change **must start in the OpenSpec flow on its own `feat/<name>` branch** — never implement directly.

### Required Tools

- PHP 8.5, Composer
- Node.js 18+ (the OpenSpec CLI is installed locally via `npm install`)
- [Claude Code](https://claude.ai/code) (recommended — skills are pre-configured in `.claude/`)

### One-time Setup

```bash
composer setup
```

This installs PHP dependencies, the OpenSpec CLI, and activates the pre-commit hook. The OpenSpec CLI (`@fission-ai/openspec`) is pinned in `package.json` and lives in `node_modules/.bin/`. The project's skills invoke it via `npx openspec` — no global installation needed.

### Feature Branch Convention

Every change gets its own branch:

```bash
git checkout -b feat/<change-name>   # e.g. feat/card-filtering
```

Merge commits (`--no-ff`) preserve each change as one node on `main`. No squash, no rebase-merge. No direct push to `main` — always via PR with CI passing. Clean up the feature branch with `--fixup` / `--autosquash` before pushing.

### Workflow per Change

Recommended path (iterative):

```bash
git checkout -b feat/<change-name>
/opsx:new <change-name>             # creates skeleton
/opsx:continue                      # repeat until all artifacts done (isComplete: true)
git add openspec/ && git commit -m "docs(<change-name>): add proposal, design and tasks"
/opsx:apply                         # TDD — implement tasks one by one
/opsx:verify                        # fix all CRITICALs
# AI Review (laravel-simplifier)
/opsx:sync                          # merge delta specs into main specs
git add openspec/ && git commit -m "docs(<change-name>): sync specs"
/opsx:archive
git add openspec/ && git commit -m "docs(<change-name>): archive change"
git fetch origin && git rebase -i --autosquash origin/main
git push -u origin feat/<change-name>
gh pr create --title "feat(<change-name>): <description>"
gh pr merge --merge --delete-branch
git checkout main && git pull && git remote prune origin
```

Express alternative (scope is clear, all artifacts in one step):

```bash
/opsx:propose <change-name>   # or: /opsx:new + /opsx:ff
```

See `.ai/guidelines/openspec-flow.md` for the full workflow with all commands, checkpoints, and commit conventions.

Use the change name as the commit scope on every commit on that branch:

```
docs(card-filtering): add proposal, design and tasks
feat(card-filtering): add color filter to cards endpoint
fix(card-filtering): correct empty result handling
docs(card-filtering): sync specs
docs(card-filtering): archive change
```

Multiple commits per phase are fine — commit as often as makes sense.

## TDD

Tests are written **before** implementation. A pre-commit hook enforces this — commits are blocked when tests fail.

```bash
# Run all tests
php artisan test --compact

# Run a specific test file or filter
php artisan test --compact --filter=CardTest
```

## Code Style

[Laravel Pint](https://laravel.com/docs/pint) is used for formatting. Run it before committing:

```bash
vendor/bin/pint --dirty
```

Coding standards follow the [Spatie PHP/Laravel Guidelines](docs/spatie-guidelines.md).

## Reporting Issues

Open a GitHub issue with a clear description of the problem and steps to reproduce.
