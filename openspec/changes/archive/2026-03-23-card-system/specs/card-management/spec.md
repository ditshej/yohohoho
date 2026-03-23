## ADDED Requirements

### Requirement: Card data model
The system SHALL store OPTCG cards with the following attributes: card_id (unique identifier like "OP15-022"), pack_id, name, rarity, category, colors (multi-value), cost, power, counter, types (multi-value), effect text, trigger text, image URL, and whether the card was manually created.

#### Scenario: Card with all attributes
- **WHEN** a card is created with all attributes populated
- **THEN** the system persists all attributes and they are retrievable

#### Scenario: Card with minimal attributes
- **WHEN** a card is created with only required attributes (card_id, name, category, colors)
- **THEN** the system persists the card with nullable fields left empty

#### Scenario: Unique card_id enforced
- **WHEN** a card with a card_id that already exists is created
- **THEN** the system rejects it with a validation error

### Requirement: Card enums
The system SHALL use enums for card category (Leader, Character, Event, Stage), card color (Red, Green, Blue, Purple, Black, Yellow), and card rarity (Leader, Common, Uncommon, Rare, SuperRare, SecretRare).

#### Scenario: Valid category
- **WHEN** a card is created with category "Character"
- **THEN** the system accepts and stores the category

#### Scenario: Invalid category
- **WHEN** a card is created with an unknown category
- **THEN** the system rejects it with a validation error

### Requirement: Manual card creation
The system SHALL provide a web form to manually create cards with all attributes. Manually created cards MUST be flagged with `is_manually_created = true`.

#### Scenario: Create card via form
- **WHEN** a user fills in the card creation form and submits
- **THEN** the system creates the card and redirects to the card detail view

#### Scenario: Validation errors shown
- **WHEN** a user submits the form with missing required fields
- **THEN** the system shows validation errors and preserves the form input

### Requirement: Card listing
The system SHALL display all cards in a list view at `/cards`, showing card_id, name, category, colors, and cost.

#### Scenario: List all cards
- **WHEN** a user visits `/cards`
- **THEN** the system displays all cards ordered by card_id

#### Scenario: Empty state
- **WHEN** no cards exist in the database
- **THEN** the system shows an empty state message

### Requirement: Card detail view
The system SHALL display all card attributes on a detail page at `/cards/{card}`.

#### Scenario: View card details
- **WHEN** a user visits `/cards/{card}`
- **THEN** the system displays all stored attributes for that card

### Requirement: Card deletion
The system SHALL allow deleting a card from the detail view.

#### Scenario: Delete card
- **WHEN** a user deletes a card
- **THEN** the system removes the card and redirects to the card list

### Requirement: Card factory and seeder
The system SHALL provide a factory for generating test cards and a seeder that creates the Brook OP15-022 leader card plus sample Green/Black cards.

#### Scenario: Seeder creates Brook leader
- **WHEN** the database seeder runs
- **THEN** a card with card_id "OP15-022", name "Brook", category "Leader", and colors ["Green", "Black"] exists
