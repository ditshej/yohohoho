## 1. Tests

- [x] 1.1 Test: `ImportCardsFromApiRequest` — validiert URL (required, url) und optionalen Farbfilter
- [x] 1.2 Test: `CardsImportController@storeFromApi` — erfolgreicher Import, API-Fehler, ungültiges JSON
- [x] 1.3 Test: Import-Formular zeigt zwei Tabs (File Upload | API Import)

## 2. Backend

- [x] 2.1 `php artisan make:request ImportCardsFromApiRequest` — Felder: `url` (required|url), `colors` (optional array)
- [x] 2.2 `storeFromApi()`-Action im `CardsImportController` — HTTP-GET via `Http::timeout(5)->get($url)`, Fehlerbehandlung, Übergabe an `CardImportService`
- [x] 2.3 Route `POST /cards-import/api` → `cardsImport.storeFromApi` in `routes/web.php`

## 3. Frontend

- [x] 3.1 View `cards-import/create.blade.php` um Tab-Navigation erweitern (Alpine.js `x-data` für aktiven Tab)
- [x] 3.2 API-Import-Tab: URL-Feld (vorausgefüllt mit `https://op-cards.ditshej.ch/api/cards`) + Farbfilter-Checkboxen + Submit

## 4. Abschluss

- [x] 4.1 `vendor/bin/pint --dirty` ausführen
- [x] 4.2 Alle Tests grün (`php artisan test`)
