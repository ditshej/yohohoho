# Brook OP15 Deck Simulator

## Vision

A web tool that simulates how deck and trash size change turn by turn when playing the Brook OP15 Leader in the One Piece TCG. Goal: find out which turn Brook "dies" — taking into account Brook's trash effect, DON!! costs, and cards with trash mechanics. Plans are stateless and shareable via URL.

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
- **Deck -> Trash** (trash_from_deck): speeds up the countdown
- **Trash -> Deck** (return_from_trash): slows down the countdown
- **Deck -> Hand** (draw): slightly speeds up the countdown

## Non-Goals

- No generic simulator for other leaders
- No opponent simulation / combat
- No authentication / multi-user
- No individual card tracking (which specific card is where) — only counts
- No deck builder / deck management
- No manual card CRUD (create/store/destroy)
- No database persistence for simulations
- No ActionType Enum / DB-backed simulation turns

## Architecture Decisions

| # | Decision | Rationale |
|---|----------|-----------|
| 1 | Laravel + Blade | Existing stack |
| 2 | Alpine.js | Lightweight reactivity for Turn Planner |
| 3 | Pest | PHP unit tests for SimulationEngine |
| 4 | Mobile-first | Full-Width Rows, Bottom Sheet on mobile |
| 5 | API import | Card data from `op-cards.ditshej.ch` (live) |
| 6 | URL-state | No DB needed; plans are shareable/bookmarkable |
| 7 | No Deck Model | Cards placed directly in Turn Planner; no 50-card rule |
| 8 | PHP Simulation | Engine in PHP, frontend only displays results |
| 9 | Counter-based simulation | We track `deckSize`, `trashSize`, `handSize`, `donAvailable`, not which specific cards are where. The user defines what happens per turn, so no randomness needed. |
| 10 | DON!! tracking | Cards can only be played if enough DON!! is available. Validation in the Turn Planner. |
| 11 | CardEffect as separate concept | Simulation-relevant effects are stored as structured data, not parsed from the effect text at runtime. |
| 12 | TDD | Write tests first, then implement. Comprehensive test coverage. |
| 13 | SQLite | Single-user tool, no concurrency requirements. |
| 14 | No Auth | Single-user tool. |

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
  5. End-of-Turn Check: deckSize <= 0 -> turn plays out, Brook dies at the end of THIS turn
     (Other leaders die immediately when deck = 0, Brook finishes the turn)
```

## Services

- **CardImportService** — Imports cards from external API (`op-cards.ditshej.ch`)
- **SimulationEngine** — Core simulation (run, processTurn)
- **EffectResolver** — Resolves CardEffects into state changes

## Controllers / Routes

| Controller | Actions | Notes |
|---|---|---|
| `CardsController` | `index`, `show` | No create/store/destroy |
| `CardsImportController` | `create`, `store` | Upload form + import |
| `CardApiController` | `index` (JSON) | Green/Black cards with CardEffects; filterable |
| `SimulationApiController` | `store` (JSON) | Plan -> results (stateless POST) |

## UI Flow (Blade + Tailwind 4 + Alpine.js)

1. **Cards** (`/cards`) — Card list with filters, import button
2. **Turn Planner** (`/turn-planner`):
   - Mobile-first, Full-Width Rows per turn
   - Per turn: Deck/Trash counter, Draw, Brook toggle, DON!! (used/max), card slots
   - Card picker: Bottom Sheet (mobile) / Sidebar (desktop)
   - Filters: effect type, cost, category, text search
   - Globals: 1st/2nd toggle, death-turn display
   - Alpine.js + AJAX to Simulation API
   - URL-state encoding for sharing/bookmarking

## Phases

Each phase is implemented as its own OpenSpec Change (TDD: tests first).

### Phase 1: Card System — DONE

Changes: card-system, card-import, card-management, api-import, ci-pipeline

### Phase 2: Card API

- JSON endpoint for cards with CardEffects
- Filtered to Green/Black (Brook-playable)
- Supports: effect-type, cost, category, text-search filters

### Phase 3: Simulation Engine

- `SimulationEngine` service (turn-by-turn calculation)
- `EffectResolver` service
- DON!! curve (1st/2nd), Draw logic, Brook effect
- API endpoint: POST plan -> JSON results (deck/trash/DON!! per turn, death-turn)
- Comprehensive Pest tests

### Phase 4: Turn Planner UI

- Mobile-first layout, Full-Width Rows
- Card Picker (Bottom Sheet / Sidebar)
- Alpine.js integration with Simulation API
- URL-state encoding/decoding

### Phase 5: Polish

- Defined once Phase 4 is complete

## External Dependencies

- `op-cards.ditshej.ch` — Card API, **already live**

## Verification

- `php artisan test --compact` after each phase
- `vendor/bin/pint --dirty --format agent` after PHP changes
- Brook without extra effects: deck empty after ~8 turns (41 / 5 per turn)
- DON!! curve: Going first Turn 1 = 1, Turn 5 = 9
