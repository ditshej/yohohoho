## Why

The Brook OP15 Deck Simulator needs a card data foundation before any deck building or simulation can happen. Cards are the core entity — every other feature (decks, simulation, UI) depends on having cards in the database with their attributes and effects.

## What Changes

- Add `CardCategory`, `CardColor`, and `CardRarity` enums
- Create `Card` model with migration, factory, and seeder
- Create `CardEffect` model to store simulation-relevant effects per card (trash from deck, return from trash, draw)
- Build `CardImportService` to import cards from vegapull JSON format (extensible for future OPTCG API)
- Add `CardsController` for listing, viewing, and manually creating cards
- Add `CardsImportController` for uploading and processing JSON imports
- Add `StoreCardRequest` and `ImportCardsRequest` form request validation
- Create Blade views: app layout, card index, card detail, card creation form, import form
- Seed Brook OP15-022 leader and a set of sample Green/Black cards with their effects

## Capabilities

### New Capabilities
- `card-management`: CRUD operations for OPTCG cards — storing card attributes (id, name, colors, cost, power, effect text, etc.), manual creation, and listing with filters
- `card-import`: importing cards from vegapull JSON files with color filtering and deduplication, extensible for future API data sources
- `card-effects`: storing simulation-relevant effects (trash_from_deck, return_from_trash, draw) as structured data per card, separate from the raw effect text

### Modified Capabilities
_None — this is the first change, no existing specs._

## Impact

- New database tables: `cards`, `card_effects`
- New enums: `CardCategory`, `CardColor`, `CardRarity`, `EffectType`
- New routes: `/cards`, `/cards/create`, `/cards/{card}`, `/cards-import/create`
- New app layout (Blade) used by all subsequent views
- Alpine.js dependency added via npm

## Non-goals

- Deck building (Phase 2)
- Simulation engine (Phase 3)
- Card image display (Phase 5)
- External API integration (separate project)
- Authentication or user management
