## 1. GitHub Actions Workflow

- [x] 1.1 Create `.github/workflows/ci.yml` with `tests` job: checkout, setup PHP 8.4, composer install, create SQLite DB, run migrations, run `php artisan test --compact`
- [x] 1.2 Add `lint` job to the workflow: checkout, setup PHP 8.4, composer install, run `vendor/bin/pint --test`
- [x] 1.3 Configure triggers for `push` (all branches) and `pull_request` events
- [x] 1.4 Verify both jobs run in parallel (no `needs` dependency between them)

## 2. Documentation

- [x] 2.1 Add CI section to `docs/dev-setup.md` with the workflow template and explanation
- [x] 2.2 Add CI status badge to `README.md`

## 3. Verification

- [ ] 3.1 Push to GitHub and verify both jobs run and pass
