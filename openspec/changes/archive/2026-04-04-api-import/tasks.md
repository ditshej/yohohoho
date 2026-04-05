## 1. Tests

- [x] 1.1 Test: `ImportCardsFromApiRequest` — validates URL (required, url) and optional color filter
- [x] 1.2 Test: `CardsImportController@storeFromApi` — successful import, API error, invalid JSON
- [x] 1.3 Test: Import form shows two tabs (File Upload | API Import)

## 2. Backend

- [x] 2.1 `php artisan make:request ImportCardsFromApiRequest` — fields: `url` (required|url), `colors` (optional array)
- [x] 2.2 `storeFromApi()` action in `CardsImportController` — HTTP GET via `Http::timeout(5)->get($url)`, error handling, pass to `CardImportService`
- [x] 2.3 Route `POST /cards-import/api` → `cardsImport.storeFromApi` in `routes/web.php`

## 3. Frontend

- [x] 3.1 Extend view `cards-import/create.blade.php` with tab navigation (Alpine.js `x-data` for active tab)
- [x] 3.2 API Import tab: URL field (pre-filled with `https://op-cards.ditshej.ch/api/cards`) + color filter checkboxes + submit

## 4. Finalize

- [x] 4.1 Run `vendor/bin/pint --dirty`
- [x] 4.2 All tests green (`php artisan test`)
