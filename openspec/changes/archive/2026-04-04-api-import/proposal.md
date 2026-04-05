## Why

The existing card import requires manually downloading a JSON file from the One Piece Cards API and then uploading it via the form. Since there is now a dedicated API at `https://op-cards.ditshej.ch/`, the import should fetch directly from there — without the intermediate step.

## What Changes

- New tab "API Import" in the existing import form next to the existing "File Upload" tab
- HTTP fetch of cards directly from `https://op-cards.ditshej.ch/api/cards` (URL pre-filled, editable)
- Optional color filter (same as file upload) is retained
- Existing File Upload tab remains unchanged

## Capabilities

### New Capabilities
- `api-import`: Fetch cards via HTTP request from a configurable URL and import them

### Modified Capabilities
- `card-import`: Import form gets a second tab for API import (UI extension, no break to existing logic)

## Non-goals

- Automated/scheduled synchronization (no cron job)
- Authentication against the API
- Support for multiple API endpoints simultaneously
- Changes to the data model (Card, CardEffect)

## Impact

- `CardsImportController` — new `storeFromApi` action
- `app/Http/Requests/` — new Form Request for API import
- `resources/views/cards-import/` — tab extension in the form
- `routes/web.php` — new route `POST /cards-import/api`
- `CardImportService` — can be reused unchanged
