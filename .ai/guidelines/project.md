## Enforcement

- Hooks and CI come from the rig-enforcement skill (base version 5). Do not edit `.claude/settings.json`, `.claude/hooks/base/`, `.githooks/base/` or `docs/ai-review/base.md`; project rules go into the `project/` folders and `docs/ai-review/project.md`.
- Releases are tags `vX.Y.Z` (semantic versioning). Before tagging, run the AI review from the rig-enforcement skill; the hook blocks the tag without `Status: pass`.
- `@phpstan-ignore` only with the error identifier and a reason.
- PHPStan baseline: never add entries. If an existing entry no longer matches after a code change (`ignore.unmatched`), fix the error in the code and delete the entry. Only if that is too much work for the change, update the entry's text to the same error.
- Laravel Boost only: `CLAUDE.md` and `.claude/skills/` are generated in the Paperclip container. After a change in `.ai/guidelines/`, run `php artisan boost:update` there and commit the result in the same pull request. Composer runs it only in the container. Herd is not used (`"herd_mcp": false`).
