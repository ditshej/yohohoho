## ADDED Requirements

### Requirement: Fetch cards directly from API URL
The system SHALL fetch cards via HTTP GET from a configurable URL and import them into the database.

#### Scenario: Successful API import
- **WHEN** the user enters a valid API URL and submits the form
- **THEN** the system fetches the URL, imports the cards, and shows a success message with the number of imported cards

#### Scenario: API unreachable
- **WHEN** the API URL is unreachable or a timeout occurs
- **THEN** the system shows an error message without importing any cards

#### Scenario: API returns invalid JSON
- **WHEN** the API URL returns valid JSON that is not an array of cards
- **THEN** the system shows an error message without importing any cards

### Requirement: Optional color filter for API import
The system SHALL offer the same color filter for API import as for file upload.

#### Scenario: Import with color filter
- **WHEN** the user selects one or more colors and starts the API import
- **THEN** the system imports only cards with the selected colors

#### Scenario: Import without color filter
- **WHEN** the user selects no color
- **THEN** the system imports all cards from the API response
