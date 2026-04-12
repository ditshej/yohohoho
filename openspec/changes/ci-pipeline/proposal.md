## Why

Quality enforcement currently runs only locally via pre-commit hooks (Pest tests, Pint formatting). There is no CI — a contributor who skips hooks or pushes from a misconfigured machine can break `main` without anyone noticing until the next local checkout. Adding a GitHub Actions pipeline closes this gap and makes test/lint status visible on every push and PR.

This also establishes the CI template for future projects (documented in `docs/dev-setup.md`).

## What Changes

- Add a GitHub Actions workflow (`.github/workflows/ci.yml`) that runs on push and pull request:
  - Install PHP 8.5 + Composer dependencies
  - Run Pest tests (`php artisan test --compact`)
  - Run Pint lint check (`vendor/bin/pint --test`)
- Add a CI section to `docs/dev-setup.md` so future projects get the same setup
- Add a CI badge to `README.md`

## Non-goals

- Deployment pipeline (deploy stays manual via `deploy.sh`)
- Static analysis (PHPStan/Larastan) — can be added later as a separate change
- Matrix builds for multiple PHP versions (single-version project)
- Node.js / frontend asset build in CI (not needed for test runs)

## Capabilities

### New Capabilities

- `ci-pipeline`: GitHub Actions workflow that runs tests and lint checks on push/PR

### Modified Capabilities

_None — no existing spec-level behavior changes._

## Impact

- New file: `.github/workflows/ci.yml`
- Modified file: `docs/dev-setup.md` (new section)
- Modified file: `README.md` (CI badge)
