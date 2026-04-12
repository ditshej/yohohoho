# yohohoho

![CI](https://github.com/ditshej/yohohoho/actions/workflows/ci.yml/badge.svg)

Reference Laravel project documenting the conventions, tooling, and workflow for building Laravel applications with Claude Code and OpenSpec.

## What's in here

- `docs/dev-setup.md` — Reusable checklist for setting up new Laravel projects
- `docs/spatie-guidelines.md` — Spatie PHP/Laravel coding standards optimised for AI assistants
- Pre-commit hook enforcing TDD (blocks commits when tests fail)
- Architecture test ensuring every Artisan Command has a corresponding test file

## Requirements

- PHP 8.5+
- Composer
- Node.js
- [Laravel Herd](https://herd.laravel.com)

## Installation

```bash
git clone <repo-url>
cd yohohoho
composer install
cp .env.example .env
touch database/database.sqlite
php artisan key:generate
git config core.hooksPath .githooks
```

## Testing

```bash
php artisan test --compact
```

## License

MIT
