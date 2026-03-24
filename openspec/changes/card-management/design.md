## Context

Cards can be imported via JSON upload (`CardsImportController`) and seeded (`BrookLeaderSeeder`), but there is no way to browse or view them in the UI. The deck builder (next phase) will need a card browsing interface.

Current state:
- Card model with full attribute set, enums, and factory
- Import flow working (upload JSON, optional color filter)
- Layout with nav bar (currently only "Import Cards" link)

## Goals / Non-Goals

**Goals:**
- Browsable card list with color, category, and text search filtering
- Detail page for individual cards

**Non-Goals:**
- Manual card creation, editing, or deletion — cards come from import
- Pagination (small dataset, single user)
- Card image rendering (polish phase)

## Decisions

### 1. `CardsController` follows Spatie plural naming convention
Read-only controller with index and show actions.

### 2. Query-string filtering on index
Filters (color, category, search) are passed as query parameters to `GET /cards`. This keeps the page bookmarkable and avoids form state. The controller applies filters via inline where clauses.

**Alternative considered:** Alpine.js client-side filtering — rejected because server-side filtering is simpler and works without JS.

### 3. Blade views under `resources/views/cards/`
Two views: `index.blade.php`, `show.blade.php`. Consistent with existing `cards-import/` view structure. All extend `layouts.app`.

### 4. Successful import redirects to card list
After a successful card import, redirect to `/cards` instead of back to the import form. This gives immediate feedback about what was imported.

## Risks / Trade-offs

- **JSON color column filtering** → SQLite JSON querying for color filters needs `LIKE` or `JSON_EACH`. Kept simple with `LIKE '%"Color"%'` which is sufficient for the enum values used.
