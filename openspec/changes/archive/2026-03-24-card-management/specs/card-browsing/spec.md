## ADDED Requirements

### Requirement: Card index page displays all cards
The system SHALL display a list of all cards at `GET /cards` showing card_id, name, category, rarity, colors, and cost for each card.

#### Scenario: View card list with cards in database
- **WHEN** user visits `/cards` and cards exist in the database
- **THEN** the response status is 200 and all cards are displayed

#### Scenario: View card list with empty database
- **WHEN** user visits `/cards` and no cards exist
- **THEN** the response status is 200 and an empty state message is shown

### Requirement: Filter cards by color
The system SHALL allow filtering the card list by one or more colors via query parameter `color`.

#### Scenario: Filter by single color
- **WHEN** user visits `/cards?color=Green`
- **THEN** only cards that include Green in their colors array are displayed

#### Scenario: Filter by multiple colors
- **WHEN** user visits `/cards?color=Green&color=Black`
- **THEN** only cards that include Green OR Black in their colors array are displayed

### Requirement: Filter cards by category
The system SHALL allow filtering the card list by category via query parameter `category`.

#### Scenario: Filter by category
- **WHEN** user visits `/cards?category=Character`
- **THEN** only cards with category Character are displayed

### Requirement: Search cards by name
The system SHALL allow searching cards by name via query parameter `search`. The search MUST be case-insensitive and match partial names.

#### Scenario: Search by partial name
- **WHEN** user visits `/cards?search=brook`
- **THEN** cards whose name contains "brook" (case-insensitive) are displayed

#### Scenario: Search with no results
- **WHEN** user visits `/cards?search=nonexistent`
- **THEN** an empty state message is shown

### Requirement: Combine filters
The system SHALL allow combining color, category, and search filters. All active filters MUST be applied together (AND logic).

#### Scenario: Combined color and category filter
- **WHEN** user visits `/cards?color=Green&category=Leader`
- **THEN** only cards that are Green AND have category Leader are displayed

### Requirement: View card detail
The system SHALL display a card detail page at `GET /cards/{card}` showing all card attributes: card_id, name, pack_id, category, rarity, colors, cost, power, counter, types, attributes, effect text, and trigger text.

#### Scenario: View existing card
- **WHEN** user visits `/cards/{card}` for an existing card
- **THEN** the response status is 200 and all card attributes are displayed

#### Scenario: View non-existent card
- **WHEN** user visits `/cards/{card}` for a non-existent card ID
- **THEN** the response status is 404

### Requirement: Navigation includes Cards link
The layout navigation SHALL include a "Cards" link pointing to the card index route.

#### Scenario: Cards link in navigation
- **WHEN** user views any page
- **THEN** the navigation contains a link to `/cards`
