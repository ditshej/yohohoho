## ADDED Requirements

### Requirement: Import from vegapull JSON
The system SHALL accept a JSON file in vegapull format and import cards from it. The vegapull format contains an array of objects with fields: id, pack_id, name, rarity, category, colors, cost, attributes, power, counter, types, effect, trigger, img_url.

#### Scenario: Successful import
- **WHEN** a valid vegapull JSON file is uploaded
- **THEN** the system creates card records for each entry and shows an import summary (imported count, skipped count)

#### Scenario: Invalid JSON rejected
- **WHEN** an invalid or malformed file is uploaded
- **THEN** the system shows a validation error

### Requirement: Color filtering on import
The system SHALL allow filtering cards by color during import, so only cards matching selected colors are imported.

#### Scenario: Filter by Green and Black
- **WHEN** a JSON file with cards of various colors is imported with filter ["Green", "Black"]
- **THEN** only cards containing Green and/or Black in their colors array are imported

#### Scenario: Import without color filter
- **WHEN** a JSON file is imported without specifying a color filter
- **THEN** all cards from the file are imported

### Requirement: Deduplication on import
The system SHALL upsert cards by card_id, updating existing records and creating new ones.

#### Scenario: New cards imported
- **WHEN** a JSON file with new cards is imported
- **THEN** all cards are created and the summary shows the imported count

#### Scenario: Existing cards updated
- **WHEN** a JSON file containing cards that already exist is imported
- **THEN** existing cards are updated and the summary shows the correct updated count

### Requirement: Import upload form
The system SHALL provide a web form at `/cards-import/create` to upload a JSON file for import.

#### Scenario: Upload form accessible
- **WHEN** a user visits `/cards-import/create`
- **THEN** the system displays a file upload form with an optional color filter selection

#### Scenario: Successful upload redirects with summary
- **WHEN** a user uploads a valid JSON file
- **THEN** the system processes the import and redirects back with a success message showing import counts

### Requirement: Extensible import architecture
The CardImportService SHALL accept a Collection of card data arrays as input, decoupled from the file parsing. The file parsing is handled by the controller, the import logic by the service.

#### Scenario: Service accepts parsed data
- **WHEN** the CardImportService receives a Collection of card data (regardless of source)
- **THEN** the system processes and imports the cards the same way

## MODIFIED Requirements

### Requirement: Import-Formular hat zwei Tabs
Das Formular unter `GET /cards-import/create` SHALL zwei Tabs anbieten: "File Upload" (bestehend) und "API Import" (neu).

#### Scenario: File Upload Tab ist aktiv per Default
- **WHEN** der User `/cards-import/create` aufruft
- **THEN** ist der "File Upload"-Tab aktiv und das bestehende Formular sichtbar

#### Scenario: Wechsel zum API-Import-Tab
- **WHEN** der User auf den "API Import"-Tab klickt
- **THEN** wird das API-Import-Formular mit vorausgefüllter URL angezeigt
