## Why

Der bestehende Karten-Import erfordert das manuelle Herunterladen einer JSON-Datei von der One Piece Cards API und das anschliessende Hochladen ins Formular. Da es jetzt eine eigene API unter `https://op-cards.ditshej.ch/` gibt, soll der Import direkt von dort abrufen — ohne Zwischenschritt.

## What Changes

- Neuer Tab "API Import" im bestehenden Import-Formular neben dem bestehenden "File Upload"-Tab
- HTTP-Abruf der Karten direkt von `https://op-cards.ditshej.ch/api/cards` (URL vorausgefüllt, änderbar)
- Optionaler Farbfilter (wie beim File-Upload) bleibt erhalten
- Bestehender File-Upload-Tab bleibt unverändert

## Capabilities

### New Capabilities
- `api-import`: Karten per HTTP-Request von einer konfigurierbaren URL abrufen und importieren

### Modified Capabilities
- `card-import`: Import-Formular erhält zweiten Tab für API-Import (UI-Erweiterung, kein Bruch der bestehenden Logik)

## Non-goals

- Automatische/geplante Synchronisation (kein Cron-Job)
- Authentifizierung gegenüber der API
- Unterstützung mehrerer API-Endpoints gleichzeitig
- Änderungen am Datenmodell (Card, CardEffect)

## Impact

- `CardsImportController` — neue `storeFromApi`-Action
- `app/Http/Requests/` — neue Form Request für API-Import
- `resources/views/cards-import/` — Tab-Erweiterung im Formular
- `routes/web.php` — neue Route `POST /cards-import/api`
- `CardImportService` — kann unverändert wiederverwendet werden
