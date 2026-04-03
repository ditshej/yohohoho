## MODIFIED Requirements

### Requirement: Import-Formular hat zwei Tabs
Das Formular unter `GET /cards-import/create` SHALL zwei Tabs anbieten: "File Upload" (bestehend) und "API Import" (neu).

#### Scenario: File Upload Tab ist aktiv per Default
- **WHEN** der User `/cards-import/create` aufruft
- **THEN** ist der "File Upload"-Tab aktiv und das bestehende Formular sichtbar

#### Scenario: Wechsel zum API-Import-Tab
- **WHEN** der User auf den "API Import"-Tab klickt
- **THEN** wird das API-Import-Formular mit vorausgefüllter URL angezeigt
