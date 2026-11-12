<laravel-boost-guidelines>
=== .ai/coding-style rules ===

## Coding Style

### Collections over Loops

- Prefer `Collection` methods and array functions (`array_map`, `array_filter`, `array_reduce`) over `foreach` and other loops
- Laravel's Illuminate Collection is the go-to for transforming, filtering, and aggregating data
- Use `collect()` to wrap arrays when chaining multiple operations
- Only fall back to loops when performance-critical or when a loop is genuinely clearer

### Spatie PHP/Laravel Conventions

- Happy path last, avoid `else`, use early returns
- Only `up()` in migrations, never `down()`
- Plural controller names (`PostsController`), CRUD methods only
- Array notation for validation rules
- Use `config()` helper, never `env()` outside config files
- Typed properties over docblocks, constructor property promotion
- kebab-case URLs, camelCase route names
- Self-documenting code over comments

=== .ai/commits rules ===

## Conventional Commits

Format: `<type>[optional scope]: <description>`

Types: `feat`, `fix`, `docs`, `style`, `refactor`, `perf`, `test`, `build`, `ci`, `chore`

- Scope optional in parentheses: `feat(auth): add login endpoint`
- Breaking changes: `!` before the colon: `feat!: remove legacy API`
- Description: imperative mood, lowercase, no trailing period
- **OpenSpec changes:** use the change name as scope for every commit on that branch:
  `docs(list-packs): add proposal, design and tasks`
  `feat(list-packs): add packs() and pack() endpoints`
  `refactor(list-packs): apply review feedback`
  `docs(list-packs): archive change`

=== .ai/openspec-flow rules ===

## Git + OpenSpec Feature Branch Flow

## Product Level (before any change)

Before starting the first OpenSpec change on a new product or roadmap block, run the product-level phase:

```
/brainstorm <topic>   # divergent ideation → writes openspec/product/<topic>/briefing.md
/clear                # reset context between phases (briefing persists in the file)
/grill-me <topic>     # align phase: reads briefing, interviews you → writes openspec/product/<topic>/aligned.md
/clear                # reset context again
[manual step]         # vertical slicing: create openspec/ROADMAP.md from the capabilities in the briefing
```

### Product Artifact Storage

Each product-level phase writes its output to the repo so it survives `/clear` and can be read by the next phase:

```
openspec/product/
└── <topic>/
    ├── briefing.md     # /brainstorm output (problem, vision, constraints, rough capabilities)
    └── aligned.md      # /grill-me output (agreed decisions, resolved questions)
```

`<topic>` is a kebab-case name for the product or roadmap block (e.g., `card-management`, `deck-builder`). Commit the files after each phase.

### Context Reset Between Phases

Run `/clear` between Brainstorm, Align, and Roadmap phases to avoid context pollution — each phase should start fresh. The skills read previous artifacts from `openspec/product/<topic>/` rather than relying on chat history.

### ROADMAP.md Format

`openspec/ROADMAP.md` is the output of the vertical slicing step. Each item maps to one OpenSpec change:

```markdown
# Roadmap

## In Progress
- [ ] <change-name> — <one-line description> (briefing: openspec/product/<topic>/)

## Planned
- [ ] <change-name> — <one-line description>

## Done
- [x] <change-name> — <description> (archived: openspec/changes/archive/YYYY-MM-DD-<name>/)
```

Checkpoints:
- **After brainstorm**: Briefing in `openspec/product/<topic>/briefing.md`. Does the scope make sense? Proceed to grill-me?
- **After grill-me**: Aligned doc in `openspec/product/<topic>/aligned.md`. Are decisions aligned? Proceed to roadmap?
- **After roadmap**: `openspec/ROADMAP.md` committed. Each capability sliced into a named change? Start `/opsx:new <first-change>`.

These steps are optional but recommended when starting a new product increment. For small, well-understood changes you can skip directly to `/opsx:new`.

### Model Selection

Thinking-heavy commands run on Opus 4.7; implementation commands use the session default (Sonnet):

| Command | Model |
|---|---|
| `/brainstorm`, `/grill-me`, `/recap`, `/opsx:explore` | Opus 4.7 (pinned via `model: opus` in frontmatter) |
| `/opsx:apply`, `/opsx:verify`, `/opsx:sync`, `/opsx:archive`, others | Session default (Sonnet) |

### Autonomous Mode (AGENT_MISSION)

If `openspec/AGENT_MISSION.md` exists at session start, read it and follow its instructions. It defines which roadmap to work through, whether to skip per-change checkpoints, and the mandatory stop format. A copy-paste template is available at `openspec/AGENT_MISSION.md.example`.

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

# 7. Recap
# /recap — AI explains: how it works, step-by-step flow, Mermaid diagram (mandatory)
# → Developer confirms understanding before proceeding

# 8. Refactor
# Use /simplify skill or laravel-simplifier agent with this checklist:
#   - "What is the smallest safe change that makes this codebase better?"
#   - "Can a new developer follow the flow without mental jumps?"
#   - "Which refactoring steps preserve behavior but improve structure?"
# → Commit(s): "refactor(<change-name>): <specific improvement>"

# 9. Sync specs
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
| * refactor(list-packs): simplify response transformation
| * refactor(list-packs): apply review feedback
| * feat(list-packs): add packs() and pack() endpoints
| * docs(list-packs): add proposal, design and tasks
|/
*   Merge pull request #42 from feat/prev-change
```

Use `git log --first-parent main` to see only the merge commits (one per change).

Each feature follows: Explore → New → Continue → Implement → Verify → Review → Recap → Refactor → Sync → Archive → PR → Merge.
Use the change name as commit scope for every commit on that branch.
Multiple commits per phase are fine — commit as often as makes sense (feat, fix, test, refactor, etc.).

=== .ai/project rules ===

## Enforcement

- Hooks and CI come from the rig-enforcement skill (base version 4). Do not edit `.claude/settings.json`, `.claude/hooks/base/`, `.githooks/base/` or `docs/ai-review/base.md`; project rules go into the `project/` folders and `docs/ai-review/project.md`.
- Releases are tags `vX.Y.Z` (semantic versioning). Before tagging, run the AI review from the rig-enforcement skill; the hook blocks the tag without `Status: pass`.
- `@phpstan-ignore` only with the error identifier and a reason.
- PHPStan baseline: never add entries. If an existing entry no longer matches after a code change (`ignore.unmatched`), fix the error in the code and delete the entry. Only if that is too much work for the change, update the entry's text to the same error.
- Laravel Boost only: `CLAUDE.md` and `.claude/skills/` are generated in the Paperclip container. After a change in `.ai/guidelines/`, run `php artisan boost:update` there and commit the result in the same pull request. Composer runs it only in the container. Herd is not used (`"herd_mcp": false`).

=== .ai/tdd rules ===

## TDD

- Write tests FIRST, then implement
- No feature without tests
- Pre-commit hook blocks commits when tests fail

=== foundation rules ===

# Laravel Boost Guidelines

## Foundational Context

This application is a Laravel application running on PHP 8.5. Always use the APIs that match the installed major version of each package — do not assume a version.

Before relying on a package's API, confirm its installed version:
- PHP packages: run `composer show --direct` to list direct dependencies with versions, or `composer show <vendor/package>` for a single package.
- JS packages: check `package.json` for the installed versions.

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If a frontend change doesn't show in the UI or you get a "Unable to locate file in Vite manifest" error, run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

=== boost rules ===

# Laravel Boost

## Project Rules

- This project contains committed, area-grouped rules in `.ai/rules` when that directory exists, including path-scoped framework guidelines under `.ai/rules/boost`. Before you enter plan mode or create/edit any file, you MUST first: open @.ai/rules/index.md (it maps file globs to rule files), read every rule file whose globs cover the path(s) in scope, and run `grep -rin 'keyword' .ai/rules` to catch what a path match alone misses. Do not write code until you have read and are following every matching rule. If `.ai/rules` does not exist, continue without it.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.

=== tests rules ===

# Test Enforcement

- Add or update tests for behavior and logic changes when a test provides meaningful regression coverage.
- Pure copy, styling, and layout-only changes do not require new or updated tests.
- When test coverage applies, run the affected tests and ensure they pass.
- Test the changed behavior and its important failure modes, but do not add tests beyond them.
- Read the `testing-best-practices` skill before writing tests.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== pest/core rules ===

# Pest

- This project uses Pest. Create tests with `php artisan make:test --pest {name}`.
- Do not include the test suite directory in `{name}`. Use `SomeFeatureTest`, not `Feature/SomeFeatureTest`.
- Read the `testing-best-practices` skill for guidance on coverage, naming, structure, dependency isolation, and review.
- Do not delete tests or test files without approval. They are part of the application.

## Running Tests

- Run the narrowest set of tests that covers the change. Pass a file path or `--filter=testName` to `php artisan test --compact`.
- Rerun a test after each change to it.
- Run `vendor/bin/pest` to call the test runner directly. It accepts the same file path and `--filter=testName` arguments.

</laravel-boost-guidelines>
