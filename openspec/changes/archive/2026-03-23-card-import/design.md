## Context

The Card and CardEffect models, enums, factories, and migrations already exist (from card-system change). This change adds the import pipeline to populate the database from vegapull JSON files.

## Goals / Non-Goals

**Goals:**
- Import cards from vegapull JSON with one upload
- Filter by color during import
- Upsert to handle re-imports gracefully
- Decouple parsing from import logic for future API extensibility

**Non-Goals:**
- Card CRUD UI (separate change)
- CardEffect import (effects are manually tagged, not in vegapull data)
- Automated/scheduled imports

## Decisions

### 1. CardImportService accepts Collection, not file

The service takes a `Collection` of card data arrays. The controller handles file reading and JSON decoding. This way the same service can be called from an Artisan command or API endpoint later.

**Alternative considered:** Service reads the file directly — rejected because it couples I/O to business logic.

### 2. Upsert by card_id

Use `Card::updateOrCreate(['card_id' => ...], [...])` for each card. This is simple, safe, and idempotent.

**Alternative considered:** Batch upsert with `DB::table()->upsert()` — rejected because we need Eloquent casts to handle enum/JSON columns correctly.

### 3. Color filtering in the service

The service accepts an optional array of `CardColor` enums. If provided, cards not matching any of the colors are skipped before upsert.

### 4. Vegapull field mapping

Vegapull uses `id` for what we call `card_id`. The mapping happens in the service:
- `id` → `card_id`
- `img_url` → `img_url`
- All other fields map 1:1

### 5. App layout created here if missing

The import form needs a layout. If `layouts/app.blade.php` doesn't exist yet, we create a minimal one with navigation and Tailwind 4.

## Risks / Trade-offs

**[Risk]** Large JSON files could be slow with individual upserts → Acceptable for now (<1000 cards per set). Can optimize with batch operations if needed later.

**[Trade-off]** No CardEffect import — vegapull doesn't include structured effect data. Effects must be tagged manually or by a future parsing system.
