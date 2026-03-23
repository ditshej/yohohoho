## 1. Enums and Base Setup

- [x] 1.1 Create CardCategory enum (Leader, Character, Event, Stage)
- [x] 1.2 Create CardColor enum (Red, Green, Blue, Purple, Black, Yellow)
- [x] 1.3 Create CardRarity enum (Leader, Common, Uncommon, Rare, SuperRare, SecretRare)
- [x] 1.4 Create EffectType enum (TrashFromDeck, ReturnFromTrash, Draw)
- [x] 1.5 Install Alpine.js via npm

## 2. Card Model Tests

- [x] 2.1 Write tests for Card model (creation, attributes, JSON casts, unique card_id)
- [x] 2.2 Write tests for CardEffect model (creation, relationship to Card, EffectType cast)
- [x] 2.3 Write tests for Card-CardEffect relationship (hasMany, eager loading, empty collection)

## 3. Card Model Implementation

- [x] 3.1 Create Card migration (all columns per spec, JSON columns for colors/types/attributes)
- [x] 3.2 Create Card model with casts, fillable, and relationships
- [x] 3.3 Create CardEffect migration (card_id FK, effect_type, amount, condition)
- [x] 3.4 Create CardEffect model with casts and belongsTo relationship
- [x] 3.5 Create Card factory
- [x] 3.6 Create CardEffect factory
- [x] 3.7 Create BrookLeaderSeeder with Brook OP15-022 and sample Green/Black cards
- [x] 3.8 Run migrations and verify tests pass

## 4. Finalize

- [x] 4.1 Run pint to format all PHP files
- [x] 4.2 Run full test suite
