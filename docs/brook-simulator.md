# Brook OP15 Deck Simulator

## Vision

A web tool that simulates how deck and trash size change turn by turn when playing the Brook OP15 Leader in the One Piece TCG. Goal: find out which turn Brook "dies" — taking into account Brook's trash effect, DON!! costs, and cards with trash mechanics.

## Domain: One Piece TCG Rules

### Basic Rules
- Deck: 50 cards
- Setup order: First draw 5 hand cards, then set aside Life cards one by one from the deck (top Life = last from deck)
- Per turn: draw 1 card + add 2 DON!!
- Exception: First player, Turn 1 — no draw, only +1 DON!!
- DON!! Deck: 10 DON!! cards (separate resource for playing cards)

### DON!! Curve
- Going First: Turn 1 +1 DON!! (no draw), then +2 per turn, max 10
- Going Second: Turn 1 +2 DON!! + Draw, then +2 per turn, max 10
- Cards cost DON!! to play (cost value on the card)

### Brook OP15-022 Leader (Green/Black)
- 5000 Power, 4 Life, Straw Hat Crew
- Effect text:
  > Under the rules of this game, you do not lose when your deck has 0 cards. You lose at the end of the turn in which your deck becomes 0 cards.
  > [Activate: Main] [Once Per Turn] Trash 4 cards from the top of your deck. Then, if your deck has 0 cards, set up to 1 of your Characters as active.

### Initial State (Brook)
- deckSize: 41 (50 - 4 Life - 5 Hand)
- trashSize: 0
- handSize: 5
- life: 4
- donAvailable: 0

### Trash Mechanics
Cards can move cards in addition to the Brook effect:
- **Deck → Trash** (trash_from_deck): speeds up the countdown
- **Trash → Deck** (return_from_trash): slows down the countdown
- **Deck → Hand** (draw): slightly speeds up the countdown

## Non-Goals

- No generic simulator for other leaders
- No opponent simulation / combat
- No authentication / multi-user
- No individual card tracking (which specific card is where) — only counts

## Architecture Decisions

1. **Counter-based simulation** — We track `deckSize`, `trashSize`, `handSize`, `donAvailable`, not which specific cards are where. The user defines what happens per turn, so no randomness needed.
2. **DON!! tracking** — Cards can only be played if enough DON!! is available. Validation in the Turn Planner.
3. **CardEffect as a separate concept** — Simulation-relevant effects are stored as structured data, not parsed from the effect text at runtime.
4. **TDD** — Write tests first, then implement. Comprehensive test coverage.
5. **Card data manual** — Cards are entered manually. A dedicated OPTCG API will be built as a separate project; this project is architected so it can consume that API later (CardImportService with swappable data source).
6. **Alpine.js** for interactivity (Deck Builder, Turn Planner).
7. **SQLite** — Single-user tool, no concurrency requirements.
8. **No Auth** — Single-user tool.

## Data Model

### Card
```
cards: id, card_id (unique, "OP15-022"), pack_id, name, rarity, category,
       colors (json), cost, power, counter, types (json), effect (text),
       trigger (text), img_url, is_manually_created (bool), timestamps
```
Enums: `CardCategory` (Leader, Character, Event, Stage), `CardColor` (Red, Green, Blue, Purple, Black, Yellow), `CardRarity`

### CardEffect (simulation-relevant effects of a card)
```
card_effects: id, card_id (FK), effect_type, amount (int), condition (text nullable), timestamps
```
Enum: `EffectType` (TrashFromDeck, ReturnFromTrash, Draw)

### Deck
```
decks: id, name, description (nullable), timestamps
```

### DeckCard (Pivot)
```
deck_card: id, deck_id (FK), card_id (FK), quantity (1-4), timestamps
unique index [deck_id, card_id]
```

### Simulation
```
simulations: id, deck_id (FK), name (nullable), going_first (bool), results (json), timestamps
```

### SimulationTurnAction (Play Plan)
```
simulation_turn_actions: id, simulation_id (FK), turn_number (int),
                         action_type, card_id (FK nullable), sort_order (int), timestamps
```
Enum: `ActionType` (PlayCard, ActivateEffect, BrookTrash)

## Core Logic: SimulationEngine

```
Per turn:
  1. DON!! Phase: donAvailable += 2 (Turn 1 going first: += 1), cap at 10
  2. Draw Phase: deckSize -= 1, handSize += 1 (Turn 1 going first: skip)
  3. Brook Ability (optional): deckSize -= 4, trashSize += 4
  4. Played cards (Play Plan) — validated against DON!! budget:
     - Play card: handSize -= 1, donAvailable -= card.cost
     - trash_from_deck: deckSize -= X, trashSize += X
     - return_from_trash: trashSize -= X, deckSize += X
     - draw: deckSize -= X, handSize += X
  5. End-of-Turn Check: deckSize <= 0 → turn plays out, Brook dies at the end of THIS turn
     (Other leaders die immediately when deck = 0, Brook finishes the turn)
```

## Services

- `CardImportService` — Imports cards from JSON (vegapull format) or future external API
- `DeckValidationService` — Validates 50-card rule, max 4 copies
- `SimulationEngine` — Core simulation (run, processTurn)
- `EffectResolver` — Resolves CardEffects into state changes

## Controllers

- `CardsController` — index, create, store, show, destroy
- `CardsImportController` — create (upload form), store (import)
- `DecksController` — CRUD
- `SimulationsController` — create, store, show

## UI Flow (Blade + Tailwind 4 + Alpine.js)

1. **Cards** (`/cards`) — Card list with filters, import button, manual entry
2. **Decks** (`/decks`) — Deck builder (search cards on left, deck on right, counter X/50)
3. **Simulation** (`/simulations/create`) — Select deck, Turn Planner with DON!! budget display
4. **Result** (`/simulations/{id}`) — Turn-by-turn deck/trash/DON!! progression, death turn marked

## Phases

Each phase is implemented as its own OpenSpec Change (TDD: tests first).

1. **Card System** — Enums, Card Model/Migration/Factory/Seeder, CardImportService, Controller, Views
2. **Deck Builder** — Deck/DeckCard Models, DeckValidationService, Controller, Views with Alpine.js
3. **Simulation Engine** — CardEffect Model, DTOs, SimulationEngine, EffectResolver, extensive tests
4. **Simulation UI** — Simulation/TurnAction Models, Controller, Turn Planner, result view
5. **Polish** — Dashboard, chart visualization, card images, responsive

## External Dependency: OPTCG Card API

Will be built as a separate project (based on vegapull/Bandai scraping). This project is architected so it can consume that API later (CardImportService with swappable data source). Until then, cards are entered manually.

## Verification

- `php artisan test --compact` after each phase
- `vendor/bin/pint --dirty --format agent` after PHP changes
- Brook without extra effects: deck empty after ~8 turns (41 / 5 per turn)
- DON!! curve: Going first Turn 1 = 1, Turn 5 = 9
