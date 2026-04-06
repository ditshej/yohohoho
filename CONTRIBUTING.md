# Contributing

## Workflow

Every change follows the OpenSpec workflow on its own feature branch:

1. **Propose** — `/opsx:propose` creates `proposal.md`, `specs/`, `design.md`, `tasks.md`
2. **Implement** — `/opsx:apply` works through tasks (TDD: tests before code)
3. **Review** — laravel-simplifier agent, then manual review
4. **Archive** — `/opsx:archive` closes the change and merges specs

See `docs/dev-setup.md` for the complete setup and conventions.

## Branches

```
feat/<change-name>      # e.g. feat/import-cards-command
```

No squash merges — full history on `main`.

## Commits

Conventional Commits, with the change name as scope on feature branches:

```
docs(my-feature): add proposal, design and tasks
feat(my-feature): add the new thing
fix(my-feature): correct edge case
refactor(my-feature): apply review feedback
docs(my-feature): archive change
```

Write `feat` and `fix` messages as user-facing descriptions — they appear in the changelog.

## Testing

TDD — write tests first. The pre-commit hook blocks commits when tests fail.
