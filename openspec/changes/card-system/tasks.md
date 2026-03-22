## 1. Enums and Base Setup

- [ ] 1.1 Create CardCategory enum (Leader, Character, Event, Stage)
- [ ] 1.2 Create CardColor enum (Red, Green, Blue, Purple, Black, Yellow)
- [ ] 1.3 Create CardRarity enum (Leader, Common, Uncommon, Rare, SuperRare, SecretRare)
- [ ] 1.4 Create EffectType enum (TrashFromDeck, ReturnFromTrash, Draw)
- [ ] 1.5 Install Alpine.js via npm

## 2. Card Model Tests

- [ ] 2.1 Write tests for Card model (creation, attributes, JSON casts, unique card_id)
- [ ] 2.2 Write tests for CardEffect model (creation, relationship to Card, EffectType cast)
- [ ] 2.3 Write tests for Card-CardEffect relationship (hasMany, eager loading, empty collection)

## 3. Card Model Implementation

- [ ] 3.1 Create Card migration (all columns per spec, JSON columns for colors/types/attributes)
- [ ] 3.2 Create Card model with casts, fillable, and relationships
- [ ] 3.3 Create CardEffect migration (card_id FK, effect_type, amount, condition)
- [ ] 3.4 Create CardEffect model with casts and belongsTo relationship
- [ ] 3.5 Create Card factory
- [ ] 3.6 Create CardEffect factory
- [ ] 3.7 Create BrookLeaderSeeder with Brook OP15-022 and sample Green/Black cards
- [ ] 3.8 Run migrations and verify tests pass

## 4. Card Import Tests

- [ ] 4.1 Write tests for CardImportService (successful import, color filtering, deduplication, invalid data handling)
- [ ] 4.2 Write tests for ImportCardsRequest validation (valid JSON file, invalid file rejected)

## 5. Card Import Implementation

- [ ] 5.1 Create CardImportService (accepts Collection of card data, upserts cards, returns summary)
- [ ] 5.2 Create ImportCardsRequest form request (file validation)
- [ ] 5.3 Create CardsImportController with create and store actions
- [ ] 5.4 Verify import tests pass

## 6. Card Controller Tests

- [ ] 6.1 Write tests for CardsController (index, create, store, show, destroy)
- [ ] 6.2 Write tests for StoreCardRequest validation (required fields, unique card_id, valid enums)

## 7. Card Controller Implementation

- [ ] 7.1 Create StoreCardRequest form request
- [ ] 7.2 Create CardsController with index, create, store, show, destroy
- [ ] 7.3 Register routes for cards and cards-import
- [ ] 7.4 Verify controller tests pass

## 8. Blade Views

- [ ] 8.1 Create app layout (layouts/app.blade.php) with navigation and Tailwind 4
- [ ] 8.2 Create card index view (list all cards with card_id, name, category, colors, cost)
- [ ] 8.3 Create card show view (all attributes)
- [ ] 8.4 Create card create form view (all fields with validation errors)
- [ ] 8.5 Create cards-import create view (file upload form)

## 9. Finalize

- [ ] 9.1 Run full test suite and verify all tests pass
- [ ] 9.2 Run pint to format all PHP files
- [ ] 9.3 Run seeder and verify Brook card exists
