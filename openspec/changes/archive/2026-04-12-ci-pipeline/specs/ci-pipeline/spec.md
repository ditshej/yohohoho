## ADDED Requirements

### Requirement: CI runs tests on every push and PR

The system SHALL have a GitHub Actions workflow that runs the Pest test suite on every push to any branch and on every pull request.

#### Scenario: Tests pass on push

- **WHEN** a commit is pushed to any branch
- **THEN** GitHub Actions runs `php artisan test --compact`
- **THEN** the workflow job reports success if all tests pass

#### Scenario: Tests fail on push

- **WHEN** a commit is pushed with failing tests
- **THEN** the workflow job reports failure
- **THEN** the failure is visible on the commit and any associated PR

### Requirement: CI runs lint check on every push and PR

The system SHALL have a GitHub Actions job that runs Pint in check mode on every push and pull request.

#### Scenario: Code style passes

- **WHEN** a commit is pushed with correctly formatted code
- **THEN** GitHub Actions runs `vendor/bin/pint --test`
- **THEN** the lint job reports success

#### Scenario: Code style fails

- **WHEN** a commit is pushed with formatting violations
- **THEN** the lint job reports failure independently of the test job

### Requirement: Tests and lint run as separate parallel jobs

The system SHALL run tests and lint as independent jobs so that both results are always visible.

#### Scenario: Lint fails but tests pass

- **WHEN** a commit has formatting violations but all tests pass
- **THEN** the test job reports success
- **THEN** the lint job reports failure

### Requirement: CI setup is documented in dev-setup

The `docs/dev-setup.md` file SHALL include a section describing how to add the CI workflow to a new project.

#### Scenario: New project setup

- **WHEN** a developer follows `docs/dev-setup.md` to set up a new project
- **THEN** the document includes the GitHub Actions workflow configuration
- **THEN** the document explains the trigger events and job structure

### Requirement: README shows CI status badge

The `README.md` SHALL display a GitHub Actions CI status badge.

#### Scenario: Badge reflects workflow status

- **WHEN** a user views the repository README
- **THEN** a badge shows the current CI status (passing/failing) for the main branch
