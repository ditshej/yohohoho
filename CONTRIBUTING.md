# Contributing

Contributions are welcome — bug reports, improvements, and new features.

## Development Workflow

This project uses [OpenSpec](https://github.com/fission-ai/openspec) for structured change management. Every new feature or significant change **must start with a proposal** — never implement directly.

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

```bash
# 1. Create a feature branch
git checkout -b feat/<change-name>

# 2. Propose the change (generates proposal, specs, design, tasks)
/opsx:propose

# 3. Commit the artifacts before implementing
git add openspec/ && git commit -m "docs(<change-name>): add proposal, design and tasks"

# 4. Implement (TDD — tests first)
/opsx:apply

# 5. Verify — checks Completeness, Correctness, Coherence against specs
/opsx:verify
# → Fix all CRITICALs before proceeding

# 6. Archive the change
/opsx:archive

# 7. Clean up fixup commits and push
git fetch origin && git rebase -i --autosquash origin/main   # no-op if rebase.autosquash is set globally
git push -u origin feat/<change-name>
gh pr create --title "feat(<change-name>): <description>"
# → CI must pass (tests + lint), then merge via GitHub ("Create a merge commit")

# 8. Merge and cleanup
gh pr merge --merge --delete-branch
git checkout main && git pull && git remote prune origin
```

Use the change name as the commit scope on every commit on that branch:

```
docs(card-filtering): add proposal, design and tasks
feat(card-filtering): add color filter to cards endpoint
fix(card-filtering): correct empty result handling
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
