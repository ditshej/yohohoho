## 1. Tests

- [ ] 1.1 Test: `ImportCardsFromApiRequest` — validiert URL (required, url) und optionalen Farbfilter
- [ ] 1.2 Test: `CardsImportController@storeFromApi` — erfolgreicher Import, API-Fehler, ungültiges JSON
- [ ] 1.3 Test: Import-Formular zeigt zwei Tabs (File Upload | API Import)

## 2. Backend

- [ ] 2.1 `php artisan make:request ImportCardsFromApiRequest` — Felder: `url` (required|url), `colors` (optional array)
- [ ] 2.2 `storeFromApi()`-Action im `CardsImportController` — HTTP-GET via `Http::timeout(5)->get($url)`, Fehlerbehandlung, Übergabe an `CardImportService`
- [ ] 2.3 Route `POST /cards-import/api` → `cardsImport.storeFromApi` in `routes/web.php`

## 3. Frontend

- [ ] 3.1 View `cards-import/create.blade.php` um Tab-Navigation erweitern (Alpine.js `x-data` für aktiven Tab)
- [ ] 3.2 API-Import-Tab: URL-Feld (vorausgefüllt mit `https://op-cards.ditshej.ch/api/cards`) + Farbfilter-Checkboxen + Submit

## 4. Abschluss

- [ ] 4.1 `vendor/bin/pint --dirty` ausführen
- [ ] 4.2 Alle Tests grün (`php artisan test`)
