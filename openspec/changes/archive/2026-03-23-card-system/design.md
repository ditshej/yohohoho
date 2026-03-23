## Context

Fresh Laravel 12 project with no existing models, controllers, or views. This is the first change — everything is greenfield. The card system is the data foundation for the entire simulator. Later phases (deck builder, simulation engine) will depend on it.

Card data will initially be entered manually or imported from vegapull JSON files. A separate OPTCG API project is planned; the import architecture must be extensible for that.

## Goals / Non-Goals

**Goals:**
- Establish the core data model for OPTCG cards
- Provide a working UI for managing cards
- Create an extensible import pipeline
- Set up the app layout (Blade) used by all future views
- Full test coverage (TDD)

**Non-Goals:**
- Card image rendering (Phase 5)
- Deck building or simulation logic
- External API calls
- Search/filtering beyond basic listing

## Decisions

### 1. JSON columns for multi-value fields (colors, types, attributes)

Use JSON columns with array casts for `colors`, `types`, and `attributes` instead of separate pivot tables.

**Why:** These are read-heavy, display-only attributes. No need to query "all red cards" via SQL joins — filtering can happen in the application layer. Simpler schema, fewer tables.

**Alternative considered:** Pivot tables (`card_color`, `card_type`) — rejected as over-engineering for a single-user tool with a small dataset.

### 2. CardEffect as a separate table (not JSON on Card)

Store simulation-relevant effects in a dedicated `card_effects` table with foreign key to `cards`, rather than as a JSON column on the card.

**Why:** Effects have structured data (type, amount, condition) that needs to be queryable and type-safe. The EffectType enum enforces valid values. One card can have multiple effects. This makes the simulation engine in Phase 3 cleaner — it can eager-load effects and iterate without parsing.

**Alternative considered:** JSON column `simulation_effects` on cards — rejected because it loses type safety and makes testing harder.

### 3. CardImportService with data array input

The `CardImportService` accepts a `Collection` of card data arrays (not a file path or JSON string). A separate step parses the file/API response into this format.

**Why:** Decouples parsing from importing. When the external API project is ready, only the parsing layer changes — the import logic stays the same.

**Alternative considered:** Single service that handles file reading + importing — rejected because it couples the data source to the import logic.

### 4. Blade app layout with Tailwind 4

Create a `resources/views/layouts/app.blade.php` layout with navigation, used by all views. Install Alpine.js now for future interactivity (Phase 2 deck builder needs it).

**Why:** Setting up the layout once avoids rework. Alpine.js is lightweight and pairs naturally with Blade.

### 5. Enums as backed PHP 8.1+ enums

Use native PHP backed enums (`string` backed) for CardCategory, CardColor, CardRarity, EffectType. Cast them in the model via Laravel's `casts()` method.

**Why:** Type safety, IDE autocompletion, validatable via `Illuminate\Validation\Rules\Enum`. Follows the Spatie guideline of using `casts()` method over `$casts` property.

## Risks / Trade-offs

**[Risk]** Vegapull JSON format may change → We validate the structure on import and skip malformed entries rather than failing the entire import.

**[Risk]** Manual card entry is tedious for many cards → This is temporary until the OPTCG API project is ready. The seeder provides Brook + sample cards for immediate testing.

**[Trade-off]** JSON columns can't be efficiently queried via SQL → Acceptable for a single-user app with <1000 cards. If filtering becomes a need, we can add indexed columns later.

**[Trade-off]** No card image display yet → Cards are identified by card_id and name. Images come in Phase 5.
