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

### Requirement: Deduplication on import
The system SHALL skip cards whose card_id already exists in the database, using upsert behavior.

#### Scenario: Duplicate cards skipped
- **WHEN** a JSON file containing cards that already exist is imported
- **THEN** existing cards are updated and the summary shows the correct count

### Requirement: Import upload form
The system SHALL provide a web form at `/cards-import/create` to upload a JSON file for import.

#### Scenario: Upload form accessible
- **WHEN** a user visits `/cards-import/create`
- **THEN** the system displays a file upload form

### Requirement: Extensible import architecture
The CardImportService SHALL be designed so the data source can be swapped (e.g., from JSON file to external API) without changing the import logic.

#### Scenario: Service accepts parsed data
- **WHEN** the CardImportService receives an array of card data (regardless of source)
- **THEN** the system processes and imports the cards the same way
