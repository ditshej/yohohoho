## Why

Cards need to be imported from external data sources (vegapull JSON files) rather than manually created one by one. The Card and CardEffect models exist (from card-system change), but there is no way to bulk-import cards yet. This is needed before building the deck builder, since a deck requires 50 cards to choose from.

## What Changes

- Create `CardImportService` that accepts a collection of card data and upserts cards into the database
- Create `ImportCardsRequest` form request for file upload validation
- Create `CardsImportController` with upload form and import processing
- Add routes for the import flow
- Create Blade views for the upload form and import results

## Capabilities

### New Capabilities
- `card-import`: importing cards from vegapull JSON files with color filtering, deduplication via upsert, and an extensible architecture for future API data sources

### Modified Capabilities
_None._

## Impact

- New service: `App\Services\CardImportService`
- New controller: `App\Http\Controllers\CardsImportController`
- New form request: `App\Http\Requests\ImportCardsRequest`
- New routes: `/cards-import/create` (GET), `/cards-import` (POST)
- New Blade views: import form, import results
- Requires app layout (will be created as part of this change if not present)

## Non-goals

- Card CRUD management UI (separate change)
- External API integration (separate project)
- Card image display
- Import scheduling or automation
