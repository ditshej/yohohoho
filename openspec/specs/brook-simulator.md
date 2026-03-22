# Brook OP15 Deck Simulator

## Vision

Ein Web-Tool das simuliert, wie sich Deck- und Trash-Grösse Zug für Zug verändern, wenn man den Brook OP15 Leader im One Piece TCG spielt. Ziel: Herausfinden in welchem Zug Brook "stirbt" — unter Berücksichtigung von Brook's Trash-Effekt, DON!!-Kosten und Karten mit Trash-Mechaniken.

## Domain: One Piece TCG Regeln

### Grundregeln
- Deck: 50 Karten
- Starting Hand: 5 Karten (vom Deck gezogen)
- Life: Karten vom Deck beiseitegelegt (Anzahl abhängig vom Leader)
- Pro Zug: 1 Karte ziehen (ausser Turn 1 going first)
- DON!! Deck: 10 DON!! Karten (separate Ressource zum Spielen von Karten)

### DON!! Kurve
- Going First: Turn 1 +1 DON!!, danach +2 pro Zug, max 10
- Going Second: Turn 1 +2 DON!!, danach +2 pro Zug, max 10
- Karten kosten DON!! zum Spielen (cost-Wert auf der Karte)

### Brook OP15-022 Leader (Green/Black)
- 5000 Power, 4 Life, Straw Hat Crew
- Effekt-Text:
  > Under the rules of this game, you do not lose when your deck has 0 cards. You lose at the end of the turn in which your deck becomes 0 cards.
  > [Activate: Main] [Once Per Turn] Trash 4 cards from the top of your deck. Then, if your deck has 0 cards, set up to 1 of your Characters as active.

### Initial State (Brook)
- deckSize: 41 (50 - 4 Life - 5 Hand)
- trashSize: 0
- handSize: 5
- life: 4
- donAvailable: 0

### Trash-Mechaniken
Karten können zusätzlich zum Brook-Effekt Karten bewegen:
- **Deck → Trash** (trash_from_deck): beschleunigt den Countdown
- **Trash → Deck** (return_from_trash): verlangsamt den Countdown
- **Deck → Hand** (draw): beschleunigt den Countdown leicht

## Non-Goals

- Kein generischer Simulator für andere Leader
- Keine Gegner-Simulation / Kampf
- Keine Authentication / Multi-User
- Kein individuelles Karten-Tracking (welche spezifische Karte wo liegt) — nur Anzahlen

## Architektur-Entscheide

1. **Zähler-basierte Simulation** — Wir tracken `deckSize`, `trashSize`, `handSize`, `donAvailable`, nicht welche konkreten Karten wo sind. Der User definiert was pro Zug passiert, daher kein Zufall nötig.
2. **DON!!-Tracking** — Karten können nur gespielt werden wenn genug DON!! vorhanden. Validierung im Turn Planner.
3. **CardEffect als separates Konzept** — Simulation-relevante Effekte werden als strukturierte Daten gespeichert, nicht zur Laufzeit aus dem Effekttext geparst.
4. **TDD** — Tests zuerst schreiben, dann implementieren. Umfassende Test-Coverage.
5. **Kartendaten manuell** — Karten werden manuell erfasst. Eine eigene OPTCG API wird als separates Projekt gebaut; dieses Projekt wird so architected, dass es diese API später konsumieren kann (CardImportService mit austauschbarer Datenquelle).
6. **Alpine.js** für Interaktivität (Deck Builder, Turn Planner).
7. **SQLite** — Single-User-Tool, keine Concurrency-Anforderungen.
8. **Kein Auth** — Single-User-Tool.

## Datenmodell

### Card
```
cards: id, card_id (unique, "OP15-022"), pack_id, name, rarity, category,
       colors (json), cost, power, counter, types (json), effect (text),
       trigger (text), img_url, is_manually_created (bool), timestamps
```
Enums: `CardCategory` (Leader, Character, Event, Stage), `CardColor` (Red, Green, Blue, Purple, Black, Yellow), `CardRarity`

### CardEffect (simulation-relevante Effekte einer Karte)
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

### SimulationTurnAction (Play-Plan)
```
simulation_turn_actions: id, simulation_id (FK), turn_number (int),
                         action_type, card_id (FK nullable), sort_order (int), timestamps
```
Enum: `ActionType` (PlayCard, ActivateEffect, BrookTrash)

## Kern-Logik: SimulationEngine

```
Pro Zug:
  1. DON!! Phase: donAvailable += 2 (Turn 1 going first: += 1), cap at 10
  2. Draw Phase: deckSize -= 1, handSize += 1 (Turn 1 going first: skip)
  3. Brook Ability (optional): deckSize -= 4, trashSize += 4
  4. Gespielte Karten (Play Plan) — validiert gegen DON!!-Budget:
     - Karte spielen: handSize -= 1, donAvailable -= card.cost
     - trash_from_deck: deckSize -= X, trashSize += X
     - return_from_trash: trashSize -= X, deckSize += X
     - draw: deckSize -= X, handSize += X
  5. End-of-Turn Check: deckSize <= 0 → Brook stirbt am Ende DIESES Zugs
```

## Services

- `CardImportService` — Importiert Karten aus JSON (vegapull-Format) oder zukünftig aus externer API
- `DeckValidationService` — Validiert 50-Karten-Regel, max 4 Kopien
- `SimulationEngine` — Kern-Simulation (run, processTurn)
- `EffectResolver` — Löst CardEffects in State-Änderungen auf

## Controllers

- `CardsController` — index, create, store, show, destroy
- `CardsImportController` — create (Upload-Form), store (Import)
- `DecksController` — CRUD
- `SimulationsController` — create, store, show

## UI Flow (Blade + Tailwind 4 + Alpine.js)

1. **Cards** (`/cards`) — Kartenliste mit Filter, Import-Button, manuelle Erfassung
2. **Decks** (`/decks`) — Deck bauen (Karten suchen links, Deck rechts, Zähler X/50)
3. **Simulation** (`/simulations/create`) — Deck wählen, Turn Planner mit DON!!-Budget-Anzeige
4. **Ergebnis** (`/simulations/{id}`) — Zug-für-Zug Deck/Trash/DON!!-Verlauf, Death-Turn markiert

## Phasen

Jede Phase wird als eigener OpenSpec Change umgesetzt (TDD: Tests zuerst).

1. **Card System** — Enums, Card Model/Migration/Factory/Seeder, CardImportService, Controller, Views
2. **Deck Builder** — Deck/DeckCard Models, DeckValidationService, Controller, Views mit Alpine.js
3. **Simulation Engine** — CardEffect Model, DTOs, SimulationEngine, EffectResolver, umfangreiche Tests
4. **Simulation UI** — Simulation/TurnAction Models, Controller, Turn Planner, Ergebnis-View
5. **Polish** — Dashboard, Chart-Visualisierung, Kartenbilder, Responsive

## Externe Abhängigkeit: OPTCG Card API

Wird als separates Projekt gebaut (basierend auf vegapull/Bandai-Scraping). Dieses Projekt wird so architected, dass es diese API später konsumieren kann (CardImportService mit austauschbarer Datenquelle). Bis dahin werden Karten manuell erfasst.

## Verifizierung

- `php artisan test --compact` nach jeder Phase
- `vendor/bin/pint --dirty --format agent` nach PHP-Änderungen
- Brook ohne Extra-Effekte: Deck leer nach ~8 Zügen (41 / 5 pro Zug)
- DON!!-Kurve: Going first Turn 1 = 1, Turn 5 = 9
