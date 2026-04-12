## Context

Quality checks (Pest tests, Pint formatting) run only via local pre-commit hooks. There is no server-side enforcement — contributors who skip hooks or push from unconfigured machines bypass all checks. GitHub Actions provides free CI for public repos and generous minutes for private repos.

## Goals / Non-Goals

**Goals:**
- Run tests and lint on every push and PR so `main` never breaks silently
- Keep the workflow simple, fast, and easy to copy to future projects
- Document the CI setup in `docs/dev-setup.md` as a reusable template

**Non-Goals:**
- Deployment automation (stays manual)
- Static analysis (separate future change)
- Multi-PHP-version matrix (single-version project)
- Frontend build or Node.js steps in CI

## Decisions

### Single workflow file

One workflow (`.github/workflows/ci.yml`) with two jobs: `tests` and `lint`.

**Why:** Separate jobs run in parallel and give independent status checks. A lint failure doesn't block seeing test results and vice versa. Two jobs in one workflow (vs. two workflow files) keeps configuration in one place.

**Alternative considered:** Single job running both sequentially — rejected because a Pint failure would hide test results.

### PHP setup with `shivammathur/setup-php`

Use the community action `shivammathur/setup-php@v2` to install PHP 8.4 (matches `composer.json` constraint and production server).

**Why:** De-facto standard for PHP CI on GitHub Actions. Handles extension installation, Composer caching, and PHP version management. Used by Laravel, Pest, and most PHP open-source projects.

### SQLite for CI database

Use SQLite (same as local dev). Create the file and run migrations in the test job.

**Why:** No external services needed, fastest setup, matches local development. The project is single-user with SQLite by design.

### Trigger on push and pull_request

Trigger on both `push` (to `main` and feature branches) and `pull_request` events.

**Why:** Push triggers catch broken commits immediately. PR triggers show status checks on the PR page before merge.

## Risks / Trade-offs

- **[Workflow drift across projects]** → Mitigated by documenting the canonical workflow in `docs/dev-setup.md`. Future projects copy from there.
