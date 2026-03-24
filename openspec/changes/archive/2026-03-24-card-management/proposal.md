## Why

Cards exist in the database (via import and seeder) but there's no way to browse or view them. The deck builder (next phase) needs a card browsing interface.

## What Changes

- Card listing page (`/cards`) with filtering by color, category, and search
- Card detail view (`/cards/{card}`)
- Navigation update: add "Cards" link to layout

## Capabilities

### New Capabilities

- `card-browsing`: Browse, filter, and view card details

### Modified Capabilities

_None — existing card models and import remain unchanged._

## Non-goals

- Manual card creation — cards come from JSON import
- Card editing or deletion — re-import via updateOrCreate handles updates
- Pagination — premature for a single-user tool with ~100-200 cards per set
- Card image display — deferred to polish phase
- Deck-related functionality — separate phase

## Impact

- New `CardsController` with index and show actions
- New Blade views under `resources/views/cards/`
- New routes under `/cards`
- Layout navigation update
