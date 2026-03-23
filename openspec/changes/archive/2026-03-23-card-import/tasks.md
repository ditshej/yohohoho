## 1. Tests

- [x] 1.1 Write tests for CardImportService (successful import, color filtering, deduplication/upsert, empty input)
- [x] 1.2 Write tests for ImportCardsRequest validation (valid JSON file, invalid file rejected, missing file)
- [x] 1.3 Write tests for CardsImportController (form display, successful upload, validation errors)

## 2. Implementation

- [x] 2.1 Create CardImportService (accepts Collection, optional color filter, upserts cards, returns summary DTO or array)
- [x] 2.2 Create ImportCardsRequest form request (file validation, optional color filter)
- [x] 2.3 Create CardsImportController with create (form) and store (process import) actions
- [x] 2.4 Register routes for cards-import

## 3. Views

- [x] 3.1 Create app layout (layouts/app.blade.php) with navigation and Tailwind 4 (if not present)
- [x] 3.2 Create cards-import create view (file upload form with optional color filter checkboxes)

## 4. Finalize

- [x] 4.1 Run full test suite and verify all tests pass
- [x] 4.2 Run pint to format all PHP files
