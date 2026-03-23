## ADDED Requirements

### Requirement: Card effect data model
The system SHALL store simulation-relevant effects per card in a separate `card_effects` table with: card_id (foreign key), effect_type, amount (integer), and optional condition text.

#### Scenario: Card with trash effect
- **WHEN** a card effect with type "TrashFromDeck" and amount 3 is created for a card
- **THEN** the effect is stored and retrievable via the card's relationship

#### Scenario: Card with multiple effects
- **WHEN** a card has both a "TrashFromDeck" and a "Draw" effect
- **THEN** both effects are stored and retrievable

### Requirement: Effect type enum
The system SHALL use an EffectType enum with values: TrashFromDeck, ReturnFromTrash, Draw.

#### Scenario: Valid effect type
- **WHEN** a card effect is created with type "TrashFromDeck"
- **THEN** the system accepts and stores the effect

#### Scenario: Invalid effect type
- **WHEN** a card effect is created with an unknown type
- **THEN** the system rejects it with a validation error

### Requirement: Card-effect relationship
The Card model SHALL have a hasMany relationship to CardEffect, allowing retrieval of all simulation-relevant effects for a card.

#### Scenario: Access effects from card
- **WHEN** a card with effects is loaded with its effects relationship
- **THEN** all associated CardEffect records are returned

#### Scenario: Card without effects
- **WHEN** a card has no effects stored
- **THEN** the effects relationship returns an empty collection
