## MODIFIED Requirements

### Requirement: Import form has two tabs
The form at `GET /cards-import/create` SHALL provide two tabs: "File Upload" (existing) and "API Import" (new).

#### Scenario: File Upload tab is active by default
- **WHEN** the user visits `/cards-import/create`
- **THEN** the "File Upload" tab is active and the existing form is visible

#### Scenario: Switch to API Import tab
- **WHEN** the user clicks the "API Import" tab
- **THEN** the API import form is displayed with the pre-filled URL
