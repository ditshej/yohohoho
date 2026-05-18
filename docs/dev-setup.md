# Dev Setup Checklist

Reusable checklist for setting up a new Laravel project with Claude Code and OpenSpec.
Based on learnings from the "yohohoho" project, inspired by [freekmurze/dotfiles](https://github.com/freekmurze/dotfiles).

---

## Language Convention

All project artifacts are written in **English**:
- Code, variable names, class names
- Comments and PHPDoc blocks
- OpenSpec artifacts (proposal.md, specs, design.md, tasks.md)
- Git commit messages
- Documentation files (README, docs/)

**Conversation language with Claude remains German.**

---

## 1. Project Init

```bash
composer create-project laravel/laravel project-name
cd project-name
composer require --dev laravel/boost laravel/pail laravel/pint pestphp/pest pestphp/pest-plugin-laravel
```

Configure SQLite in `.env`:

```
DB_CONNECTION=sqlite
```

Create the database file:

```bash
touch database/database.sqlite
```

## 2. Project Files

Every project gets four standard files at the root: `README.md`, `LICENSE`, `CONTRIBUTING.md`, and `CHANGELOG.md`.

### README.md

Use `---` horizontal dividers between sections, numbered installation steps, and tables for commands/endpoints. See `one-piece-cards-api/README.md` as a reference for a real project.

Minimal structure:

```markdown
# <Project Name>

<One-line description>

- **Live URL:** `https://...` (if applicable)

---

## Installation

**Requirements:** PHP 8.x+, Composer, Node.js, [Laravel Herd](https://herd.laravel.com)

​```bash
# 1. Clone and install
git clone <repo-url> && cd <project-name>
composer install && npm install

# 2. Configure environment
cp .env.example .env
touch database/database.sqlite
php artisan key:generate
git config core.hooksPath .githooks
​```

---

## Testing

​```bash
php artisan test --compact
​```

---

## Deployment

Requires `.env.deploy` (copy from `.env.deploy.example`):

​```bash
./deploy.sh
​```

---

## License

MIT
```

### LICENSE

```
MIT License

Copyright (c) <year> Raphael Weiss

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

### CONTRIBUTING.md

```markdown
# Contributing

Contributions are welcome — bug reports, improvements, and new features.

## Development Workflow

This project uses [OpenSpec](https://github.com/fission-ai/openspec) for structured change management.
Every new feature or significant change **must start with a proposal** — never implement directly.

### Required Tools

- PHP 8.x, Composer, Node.js
- [OpenSpec CLI](https://github.com/fission-ai/openspec): `npm install -g @fission-ai/openspec`
- [Claude Code](https://claude.ai/code) (recommended — skills are pre-configured in `.claude/`)

### Feature Branch Convention

​```bash
git checkout -b feat/<change-name>   # e.g. feat/card-filtering
​```

Merge commits (`--no-ff`) preserve each change as a single node on `main`. No squash, no rebase-merge. Clean up feature-branch WIPs with `--fixup` / `--autosquash` before pushing (see "Feature branch hygiene" below).

### Workflow per Change

​```bash
git checkout -b feat/<change-name>
/opsx:new <change-name>             # create skeleton
/opsx:continue                      # repeat until isComplete: true
git add openspec/ && git commit -m "docs(<change-name>): add proposal, design and tasks"
/opsx:apply                         # TDD — tests first
/opsx:verify                        # fix all CRITICALs
# AI Review (laravel-simplifier) — fix findings, commit
/opsx:sync                          # merge delta specs into main specs
git add openspec/ && git commit -m "docs(<change-name>): sync specs"
/opsx:archive
git add openspec/ && git commit -m "docs(<change-name>): archive change"
git fetch origin && git rebase -i --autosquash origin/main
git push -u origin feat/<change-name>
gh pr create --title "feat(<change-name>): <description>"
gh pr merge --merge --delete-branch
​```

Express alternative: `/opsx:propose <change-name>` (or `/opsx:new` + `/opsx:ff`) generates all artifacts at once.

Use the change name as the commit scope on every commit on that branch:

​```
docs(card-filtering): add proposal, design and tasks
feat(card-filtering): add color filter to cards endpoint
fix(card-filtering): correct empty result handling
docs(card-filtering): sync specs
docs(card-filtering): archive change
​```

Write `feat` and `fix` messages as user-facing descriptions — they appear in the changelog.

## TDD

Tests are written **before** implementation. A pre-commit hook enforces this — commits are blocked when tests fail.

​```bash
php artisan test --compact
php artisan test --compact --filter=CardTest
​```

## Code Style

[Laravel Pint](https://laravel.com/docs/pint) is used for formatting. Run it before committing:

​```bash
vendor/bin/pint --dirty
​```

Coding standards follow the [Spatie PHP/Laravel Guidelines](docs/spatie-guidelines.md).

## Reporting Issues

Open a GitHub issue with a clear description of the problem and steps to reproduce.
```

### CHANGELOG.md + git-cliff

Generate the changelog automatically from conventional commits using [git-cliff](https://git-cliff.org):

```bash
brew install git-cliff
```

Add `cliff.toml` to the project root:

```toml
[changelog]
header = """
# Changelog\n
All notable changes to this project will be documented in this file.\n
"""
body = """
{% if version %}\
    ## [{{ version | trim_start_matches(pat="v") }}] - {{ timestamp | date(format="%Y-%m-%d") }}
{% else %}\
    ## [Unreleased]
{% endif %}\
{% for group, commits in commits | group_by(attribute="group") %}
### {{ group | upper_first }}
{% for commit in commits %}\
- {% if commit.scope %}**{{ commit.scope }}:** {% endif %}{{ commit.message | upper_first }}
{% endfor %}
{% endfor %}\n
"""
trim = true

[git]
conventional_commits = true
filter_unconventional = true
commit_parsers = [
    { message = "^feat", group = "Features" },
    { message = "^fix", group = "Bug Fixes" },
    { message = "^perf", group = "Performance" },
    { message = "^refactor", skip = true },
    { message = "^docs", skip = true },
    { message = "^test", skip = true },
    { message = "^chore", skip = true },
    { message = "^style", skip = true },
    { message = "^build", skip = true },
    { message = "^ci", skip = true },
]
filter_commits = true
tag_pattern = "v[0-9].*"
sort_commits = "oldest"
```

The **scope** from commit messages (e.g. `feat(card-management): ...`) is displayed in the changelog entry as **card-management:** — it provides context without cluttering the description.

Generate or update the changelog:

```bash
git cliff -o CHANGELOG.md
```

Tag a release before generating to get versioned sections instead of `[Unreleased]`:

```bash
git tag v1.0.0
git cliff -o CHANGELOG.md
```

Add a composer script for convenience:

```json
"scripts": {
    "changelog": "git cliff -o CHANGELOG.md"
}
```

#### Commit message quality

`docs`, `chore`, `refactor`, and `test` commits are **filtered out** — their messages don't appear in the changelog. Only `feat`, `fix`, and `perf` do. Write those with the reader in mind:

```
# Bad — too internal
feat(card-management): implement index and show methods

# Good — user-facing
feat(card-management): add card browsing with filters and detail pages
```

## 3. Set up OpenSpec

```bash
# Install the CLI and init with Claude tools
npx @fission-ai/openspec init --tools claude

# Activate the full workflow profile with all 11 commands
npx openspec config profile custom
# → interactively select all workflows when prompted:
#   propose, explore, new, continue, apply, ff, sync, archive, bulk-archive, verify, onboard

# Force-refresh instruction files (skills + slash commands)
npx openspec update --force
```

This creates:
- `openspec/config.yaml` — project config
- `openspec/specs/` — long-lived project specs (populated by changes)
- `openspec/changes/` — short-lived work packages
- `.claude/skills/openspec-*` — skills for Claude Code Agents (11 total)
- `.claude/commands/opsx/` — slash commands (11 total, `delivery: both`)

> **CLI Note:** OpenSpec should be added as a dev-dependency in `package.json` (not global). Skills and commands may show `openspec ...` — always run as `npx openspec ...`. See Section 10 for the `composer.json` setup script pattern.

Fill `openspec/config.yaml` with project context:

```yaml
schema: spec-driven

context: |
  ## Project
  <project description>

  ## Tech Stack
  PHP 8.x, Laravel 12, Pest 4, Pint, Blade + Tailwind 4 + Alpine.js, Vite, SQLite

  ## Coding Standards
  Spatie PHP/Laravel guidelines (see docs/spatie-guidelines.md)
  Key conventions:
    - Happy path last, avoid else, use early returns
    - Only up() in migrations, never down()
    - Plural controller names (PostsController), CRUD methods only
    - Array notation for validation rules
    - Use config() helper, never env() outside config files
    - Typed properties over docblocks, constructor property promotion
    - kebab-case URLs, camelCase route names
    - Self-documenting code over comments

  ## Development Approach
  - TDD: always write tests FIRST, then implement
  - Conventional Commits

rules:
  tasks:
    - Break tasks into chunks of max 2 hours
    - Tests must be listed BEFORE implementation tasks
    - Maximum 15 tasks per change — split into smaller changes if more
  proposal:
    - Always include a "Non-goals" section
    - Keep scope small — one concern per change, not an entire feature layer
```

### OpenSpec Workflow Commands

| Command | What it does |
|---|---|
| `/opsx:explore` | Investigate options/tradeoffs (no artifacts, no code) |
| `/opsx:new` | Create change skeleton — recommended first step |
| `/opsx:continue` | Create the next artifact (one per call, loop until done) |
| `/opsx:ff` | Fast-forward — create all remaining artifacts at once |
| `/opsx:propose` | Express: skeleton + all artifacts in one step |
| `/opsx:apply` | Implement tasks (TDD) |
| `/opsx:verify` | Check Completeness/Correctness/Coherence |
| `/opsx:sync` | Merge delta specs into main specs |
| `/opsx:archive` | Move change to `openspec/changes/archive/YYYY-MM-DD-<name>/` |
| `/opsx:bulk-archive` | Archive multiple changes at once |
| `/opsx:onboard` | Guided walkthrough for new team members |

> **Rule 1:** Every new feature ALWAYS starts in the OpenSpec flow on its own `feat/<name>` branch — never implement directly, not even in plan mode. Recommended path: `/opsx:new` → `/opsx:continue`. Express path for small/clear changes: `/opsx:propose`.

> **Rule 2:** Commit after all artifacts are complete (`isComplete: true` from `npx openspec status --json`) — before implementing:
> `git add openspec/ && git commit -m "docs(<name>): add proposal, design and tasks"`

## 4. Spatie Guidelines

Copy `docs/spatie-guidelines.md` into the new project. This file contains the Spatie PHP/Laravel Coding Standards, optimized for AI Code Assistants.

Source: [freekmurze/dotfiles](https://github.com/freekmurze/dotfiles/blob/main/config/claude/laravel-php-guidelines.md)

## 5. Conventional Commits

Define the convention in `CLAUDE.md` under `## Conventional Commits`:

- Format: `<type>[optional scope]: <description>`
- Types: `feat`, `fix`, `docs`, `style`, `refactor`, `perf`, `test`, `build`, `ci`, `chore`
- Scope optional in parentheses: `feat(auth): add login endpoint`
- Breaking changes: `!` before the colon: `feat!: remove legacy API`
- Description: imperative mood, lowercase, no trailing period
- **OpenSpec changes:** use the change name as scope for every commit on that branch:
  `docs(list-packs): add proposal, design and tasks`
  `feat(list-packs): add packs() and pack() endpoints`
  `refactor(list-packs): apply review feedback`
  `docs(list-packs): archive change`

Reference: [conventionalcommits.org/en/v1.0.0](https://www.conventionalcommits.org/en/v1.0.0/)

## 6. TDD (Test-Driven Development)

Define in `CLAUDE.md` under `## Testing (TDD)`:

- Write tests FIRST, then implement the code
- Pest 4 for all tests, prefer Feature Tests
- Aim for comprehensive test coverage — no feature without tests
- `php artisan test --compact` after every change

### Pre-commit Hook

Store the hook in `.githooks/pre-commit` (committed to the repo):

```sh
#!/bin/sh

echo "Running tests before commit..."

php artisan test --compact

if [ $? -ne 0 ]; then
    echo "Tests failed. Commit blocked."
    exit 1
fi
```

Make it executable:

```bash
chmod +x .githooks/pre-commit
```

Activate by adding to the `setup` script in `composer.json`:

```json
"setup": [
    "...",
    "git config core.hooksPath .githooks"
]
```

Run once on existing projects:

```bash
git config core.hooksPath .githooks
```

### Architecture Test

Create `tests/Feature/ArchTest.php` to enforce that every Artisan Command has a corresponding test file:

```php
it('all artisan commands have a corresponding test file', function () {
    $commandFiles = collect(\Illuminate\Support\Facades\File::allFiles(app_path('Console/Commands')))
        ->filter(fn ($file) => $file->getExtension() === 'php')
        ->map(fn ($file) => $file->getPathname())
        ->toArray();

    if (empty($commandFiles)) {
        expect(true)->toBeTrue(); // no commands to check

        return;
    }

    foreach ($commandFiles as $commandFile) {
        $commandName = basename($commandFile, '.php');
        $testFile = base_path("tests/Feature/{$commandName}Test.php");

        expect(file_exists($testFile))
            ->toBeTrue("Missing test file for command: {$commandName}");
    }
});
```

This test is automatically enforced via the pre-commit hook — a command without a test file will block the commit.

## 7. Git + OpenSpec Feature Branch Flow

Every OpenSpec change gets its own feature branch and lands on `main` as a single merge commit (`--no-ff`). No squash, no rebase-merge. No direct push to `main` — always via PR with CI passing.

### Branch Naming Convention

```
feat/<change-name>      # e.g. feat/import-cards-command
```

### Workflow per Change

```bash
# 0. Explore (optional)
# /opsx:explore — investigate ideas and tradeoffs for this change
# → CHECKPOINT: Present findings to user → wait for OK before continuing

# 1. Create branch
git checkout -b feat/<change-name>

# 2. Create change skeleton
# /opsx:new <change-name>
# → shows first artifact template

# 3. Build artifacts iteratively
# /opsx:continue      # repeat until isComplete: true (check with: npx openspec status --json)
# → CHECKPOINT: Present proposal summary → wait for OK before implementing
git add openspec/ && git commit -m "docs(<change-name>): add proposal, design and tasks"

# 4. Implementation (TDD)
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
# /opsx:sync — merge delta specs into main specs
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

> `--delete-branch` deletes the remote branch on GitHub. `git remote prune origin` removes stale remote-tracking refs locally. The local branch is cleaned up automatically by `gh pr merge`.

**Express alternative** (when scope is clear — all artifacts in one step):

```bash
/opsx:propose <change-name>   # or: /opsx:new + /opsx:ff
```

### Resulting History on main

```
*   Merge pull request #43 from feat/import-cards-command
|\
| * docs(import-cards-command): archive change
| * docs(import-cards-command): sync specs
| * refactor(import-cards-command): apply review feedback
| * feat(import-cards-command): add cards:import artisan command
| * docs(import-cards-command): add proposal, design and tasks
|/
*   Merge pull request #42 from feat/pack-and-card-models
|\
| * docs(pack-and-card-models): archive change
| * docs(pack-and-card-models): sync specs
| * feat(pack-and-card-models): add Pack and Card models with migrations and factories
| * docs(pack-and-card-models): add proposal, design and tasks
|/
```

Each OpenSpec change becomes one merge-commit node on `main`'s first-parent history. Use `git log --first-parent main` to see just the change-level nodes. Use `git log --graph --oneline` to see the full graph including feature-branch commits.

Each feature follows: Explore → New → Continue → Implement → Verify → Review → Sync → Archive → PR → Merge.
Use the change name as commit scope for every commit on that branch.
Multiple commits per phase are fine — commit as often as makes sense (feat, fix, test, refactor, etc.).

### Feature branch hygiene

Because every feature-branch commit lands on `main` via the merge commit's second parent, WIP commits ("wip", "fix typo", "argh") would leak into the permanent history. Use `--fixup` + `--autosquash` to keep the branch clean without manual interactive rebases:

```bash
# During work — instead of a generic "fix" commit, mark the fixup explicitly
git commit --fixup=<target-sha>
# Produces a commit with message "fixup! <target commit subject>"

# Before pushing — collapse all fixup! commits into their targets
git rebase -i --autosquash origin/main
# Git pre-orders the TODO list; you usually just :wq
```

Make `--autosquash` the default so you don't need to remember the flag:

```bash
git config --global rebase.autosquash true
```

Use this only for small corrections (typos, follow-up tweaks). New meaningful commits stay normal. The goal: the commits that reach `main` should each stand on their own.

### Bisecting on `main`

`git bisect` finds the commit that introduced a bug via binary search. With merge commits, a naive bisect may land on an intermediate feature-branch commit (possibly with failing tests that aren't the regression you're hunting). Restrict bisect to the merge-commit nodes on `main`:

```bash
git bisect start --first-parent <bad-ref> <good-ref>
# Or set as default globally:
git config --global bisect.firstParent true
```

With `--first-parent`, each bisect step selects one OpenSpec change as a whole — the granularity that matters for production regressions.

## 8. Set up Deployment

### deploy.sh (local)

Create `deploy.sh` in the project root:

```sh
#!/bin/sh
set -e

if [ ! -f .env.deploy ]; then
    echo "Error: .env.deploy not found. Copy .env.deploy.example and fill in your credentials."
    exit 1
fi

set -a
. .env.deploy
set +a

echo "Building frontend assets..."
npm run build

echo "Uploading build assets..."
rsync -az --delete -e "ssh -p $DEPLOY_PORT" \
    public/build/ \
    $DEPLOY_USER@$DEPLOY_HOST:$DEPLOY_PATH/public/build/

echo "Deploying..."
ssh -p $DEPLOY_PORT $DEPLOY_USER@$DEPLOY_HOST -t \
    "cd $DEPLOY_PATH && DEPLOY_PHP=$DEPLOY_PHP DEPLOY_COMPOSER=$DEPLOY_COMPOSER bash ./_deploy.sh"
```

> **Note:** The rsync step is needed when the server has no Node.js. If Node is available, `npm run build` can be run in `_deploy.sh` on the server instead.

### .env.deploy.example (commit this)

```
DEPLOY_USER=user
DEPLOY_HOST=host
DEPLOY_PORT=22
DEPLOY_PATH=/path/on/server
DEPLOY_PHP=/usr/bin/php
DEPLOY_COMPOSER=/usr/bin/composer
```

Add `.env.deploy` itself to `.gitignore` — it contains real credentials.

### _deploy.sh (on the server, in the project root)

```sh
#!/bin/sh
set -e

PHP=${DEPLOY_PHP:-php}
COMPOSER=${DEPLOY_COMPOSER:-composer}

git pull origin main

$PHP $COMPOSER install --no-interaction --optimize-autoloader --no-dev

$PHP artisan migrate --force

$PHP artisan optimize:clear
```

> Adjust `DEPLOY_PHP` and `DEPLOY_COMPOSER` in `.env.deploy` to match your server's paths (`which php` and `which composer` on the server).

### Run Deploy

```bash
./deploy.sh
```

## 9. Laravel Boost / MCP Setup

Laravel Boost provides an MCP server with tools designed for Laravel projects: database queries, schema inspection, log reading, and documentation search.

Register the MCP server in Claude Code:

```bash
claude mcp add laravel-boost -- php artisan mcp:serve
```

This adds the server to `.claude/settings.json`. Verify with:

```bash
claude mcp list
```

The Boost rules (database tools, doc search, Artisan guidance) are injected into Claude's context automatically when the MCP server is active. The project-level `CLAUDE.md` should additionally include skills activation rules and project-specific conventions.

> **Important:** In projects using `laravel/boost`, `CLAUDE.md` (specifically the `<laravel-boost-guidelines>` block) is a **generated file** — it is rebuilt from `.ai/guidelines/*.md` by running `php artisan boost:install --guidelines`. Never edit the inline block directly; always edit the source files in `.ai/guidelines/` and regenerate. `CLAUDE.md` should be added to `.gitignore`.

## 10. Global `~/.claude/CLAUDE.md` (one-time, global)

Create `~/.claude/CLAUDE.md` with universal rules that apply to **all** projects. This avoids duplicating them in every project's `CLAUDE.md`.

```markdown
## Language Convention
All project artifacts in English. Conversation with Claude in German.

## Conventional Commits
Format: `<type>[scope]: <description>`
Types: feat, fix, docs, refactor, test, chore, style, perf, build, ci
OpenSpec changes: use change name as scope for every commit on that branch.
Multiple commits per phase are fine (feat, fix, test, refactor, etc.).

## Git Flow
Feature branches: `feat/<change-name>`. Merge into `main` as merge commits (`--no-ff`) — no squash, no rebase-merge. Full history on main.

## TDD
Tests first, then implementation.

## Claude Code Deny Rules
See "Claude Code Deny Rules" section of dev-setup — add these rules to `~/.claude/settings.json`.
```

The project-level `CLAUDE.md` then only needs project-specific rules.

## 11. Extend .gitignore

Add the following:

```
.claude/settings.local.json
.claude/worktrees/
.env.deploy
```

> **Note for application projects:** `docs/dev-setup.md` can also be added here — it is a personal setup document, not application code. Do not do this in a template project like `yohohoho`, where `dev-setup.md` is the product itself.

### Local Permissions (settings.local.json)

`settings.local.json` is excluded by `.gitignore` above, so each developer configures it locally. It lets you pre-approve Claude Code tool calls for this project without touching shared settings.

Recommended baseline:

```json
{
  "permissions": {
    "allow": [
      "Bash(git add:*)",
      "Bash(git commit:*)",
      "Bash(git checkout:*)",
      "Bash(git merge:*)",
      "Bash(git branch:*)",
      "Bash(git show:*)",
      "Bash(git log:*)",
      "Bash(git diff:*)",
      "Bash(vendor/bin/pint*)",
      "Bash(php artisan*)",
      "Bash(composer test*)",
      "Bash(composer install*)",
      "Bash(openspec*)",
      "Bash(npx openspec*)"
    ]
  }
}
```

Add project-specific entries (MCP tool names, allowed domains) as needed.

## 12. Claude Code Agents (global, one-time)

Set up two agents in `~/.claude/agents/`:

- **laravel-debugger.md** — Diagnoses errors, stack traces, N+1 queries, queue failures
- **laravel-simplifier.md** — Reviews and simplifies code (clarity, redundancy, naming, conventions)

Source: [freekmurze/dotfiles/config/claude/agents/](https://github.com/freekmurze/dotfiles/tree/main/config/claude/agents)

## 13. Git-Delta (global, one-time)

```bash
brew install git-delta
```

Add to `~/.gitconfig`:

```ini
[core]
    pager = delta
[interactive]
    diffFilter = delta --color-only
[delta]
    navigate = true
    side-by-side = true
    line-numbers = true
[merge]
    conflictstyle = diff3
[diff]
    colorMoved = default
```

## 14. Optional: Additional CLI Tools

```bash
brew install eza bat zoxide fzf fnm git-cliff
```

- `eza` — Better `ls` with icons
- `bat` — Better `cat` with syntax highlighting
- `zoxide` — Smart `cd` (learns directories)
- `fzf` — Fuzzy finder
- `fnm` — Fast Node.js version manager
- `git-cliff` — Changelog generator from conventional commits

## 15. Shell Aliases

Create a file `~/.aliases` and source it in `~/.zshrc`:

```bash
# Add to ~/.zshrc:
[ -f ~/.aliases ] && source ~/.aliases
```

Contents of `~/.aliases`:

```bash
# Laravel / PHP
alias a="php artisan"
alias mfs="php artisan migrate:fresh --seed"

# Pest (auto-detect pest vs phpunit)
function p() {
    if [ -f vendor/bin/pest ]; then
        vendor/bin/pest "$@"
    else
        vendor/bin/phpunit "$@"
    fi
}

# Composer
alias ci="composer install"
alias cu="composer update"
alias cr="composer require"
alias cda="composer dump-autoload"

# Claude Code
alias c="claude"
alias cy="claude --dangerously-skip-permissions"

# Git
alias nah="git reset --hard && git clean -df"
```

## 16. Claude Code Deny Rules (global)

Add deny rules to `~/.claude/settings.json`. These apply even in bypass mode (`--dangerously-skip-permissions`) and block destructive commands:

```json
{
  "permissions": {
    "deny": [
      "Bash(git push --force*)",
      "Bash(git push * --force*)",
      "Bash(git reset --hard*)",
      "Bash(git clean -f*)",
      "Bash(git clean -df*)",
      "Bash(git checkout -- .)",
      "Bash(rm -rf *)",
      "Bash(rm -rf /*)",
      "Bash(*db:wipe*)",
      "Bash(*migrate:fresh*--env=prod*)",
      "Bash(*migrate:fresh*--env=production*)",
      "Bash(*DROP DATABASE*)",
      "Bash(*DROP TABLE*)"
    ]
  }
}
```

Reference: [Safety Nets for Claude Code](https://cbox.dk/blog/safety-nets-for-claude-code-skip-permissions)

## 17. Optional: openspec/ROADMAP.md

> **TBD — will be expanded in the next iteration** together with the Brainstorm / Align / Recap / Refactor framework. The pattern below is a placeholder based on earlier convention.

For projects with multiple planned changes, create `openspec/ROADMAP.md` to list them in implementation order. Each entry gets a short description and the files it will create or modify.

Reference: `op-cards-php/openspec/ROADMAP.md`

## 18. Optional: openspec/AGENT_MISSION.md

> **TBD — will be expanded in the next iteration** together with the Brainstorm / Align / Recap / Refactor framework. The flow below is based on earlier convention and does not yet include `sync`, `recap`, or `refactor` steps.

For projects with a roadmap, `openspec/AGENT_MISSION.md` provides instructions that enable a Claude Code agent to autonomously work through the entire roadmap — one change at a time. The updated flow (post Brainstorm/Align iteration) will be: `explore → new → continue → apply → verify → ai-review → recap → refactor → sync → archive`.

Reference: `op-cards-php/openspec/AGENT_MISSION.md`

---

## 19. GitHub Actions CI

Add a CI workflow so tests and code style are enforced on every push and PR — not just locally via pre-commit hooks.

### Workflow File

Create `.github/workflows/ci.yml`:

```yaml
name: CI

on:
  push:
    branches: [main]
  pull_request:
    branches: [main]

jobs:
  tests:
    runs-on: ubuntu-latest

    steps:
      - uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.4'
          extensions: mbstring, sqlite3
          coverage: none

      - name: Get Composer cache directory
        id: composer-cache
        run: echo "dir=$(composer config cache-files-dir)" >> $GITHUB_OUTPUT

      - name: Cache Composer dependencies
        uses: actions/cache@v4
        with:
          path: ${{ steps.composer-cache.outputs.dir }}
          key: ${{ runner.os }}-composer-${{ hashFiles('**/composer.lock') }}
          restore-keys: ${{ runner.os }}-composer-

      - name: Install dependencies
        run: composer install --no-interaction --prefer-dist

      - name: Install Node.js dependencies
        run: npm ci

      - name: Build frontend assets
        run: npm run build

      - name: Prepare database
        run: |
          touch database/database.sqlite
          cp .env.example .env
          php artisan key:generate
          php artisan migrate --force

      - name: Run tests
        run: php artisan test --compact

  lint:
    runs-on: ubuntu-latest

    steps:
      - uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.4'
          extensions: mbstring
          coverage: none

      - name: Get Composer cache directory
        id: composer-cache
        run: echo "dir=$(composer config cache-files-dir)" >> $GITHUB_OUTPUT

      - name: Cache Composer dependencies
        uses: actions/cache@v4
        with:
          path: ${{ steps.composer-cache.outputs.dir }}
          key: ${{ runner.os }}-composer-${{ hashFiles('**/composer.lock') }}
          restore-keys: ${{ runner.os }}-composer-

      - name: Install dependencies
        run: composer install --no-interaction --prefer-dist

      - name: Check code style
        run: vendor/bin/pint --test
```

**Key design choices:**

- **Two parallel jobs** (`tests` + `lint`) — a lint failure doesn't hide test results and vice versa
- **`shivammathur/setup-php`** — de-facto standard for PHP on GitHub Actions
- **Composer cache** — speeds up repeated runs via `actions/cache@v4` keyed on `composer.lock`
- **SQLite** — matches local dev, no external services needed
- **Triggers on PRs to `main` + pushes to `main`** — PRs are the gate, push-trigger is the safety net after merge

### README Badge

Add a CI status badge to the top of `README.md`:

```markdown
![CI](https://github.com/<owner>/<repo>/actions/workflows/ci.yml/badge.svg)
```

Replace `<owner>/<repo>` with the actual GitHub repository path.

### Branch Protection (GitHub Ruleset)

After the CI workflow has run at least once, configure a branch ruleset on GitHub (Settings → Rules → Rulesets → New branch ruleset):

1. **Ruleset Name:** `main-protection`
2. **Enforcement status:** Active
3. **Target branches:** Include by pattern → `main`
4. **Branch rules:**
   - **Require a pull request before merging** (Required approvals: 0 for solo projects)
   - **Require status checks to pass** → Add checks: `tests`, `lint`
   - **Allowed merge methods:** Merge commits only (no squash, no rebase-merge)
   - **Require linear history:** OFF — merge commits are incompatible with this setting
5. **Bypass list:** Leave empty (even admins go through PRs)

> **Note:** The status checks `tests` and `lint` only appear in the dropdown after the workflow has run at least once on `main`.

Also configure **Settings → General**:

- Enable **"Automatically delete head branches"** so merged PR branches are cleaned up on GitHub.
- Under **Pull Requests**: enable **"Allow merge commits"**, disable **"Allow squash merging"** and **"Allow rebase merging"**.
- Under **Pull Requests → Default commit message for merge commits**: select **"Pull request title"** so merge commits inherit the PR's conventional-commit subject (e.g. `feat(list-packs): add packs() endpoint`).

---

## Open TODOs

- [ ] **`/new-laravel-project` skill** — Once this dev-setup is stable, create a Claude Code skill that automates Sections 1–7 and 10–11 for new projects (composer create-project, SQLite, project files, OpenSpec init, pre-commit hook, ArchTest, CLAUDE.md boilerplate, .gitignore).
