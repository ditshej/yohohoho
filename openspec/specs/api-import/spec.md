## ADDED Requirements

### Requirement: Karten direkt von API-URL abrufen
Das System SHALL Karten per HTTP GET von einer konfigurierbaren URL abrufen und in die Datenbank importieren.

#### Scenario: Erfolgreicher API-Import
- **WHEN** der User eine gültige API-URL eingibt und das Formular abschickt
- **THEN** ruft das System die URL ab, importiert die Karten und zeigt eine Erfolgsmeldung mit Anzahl importierter Karten

#### Scenario: API nicht erreichbar
- **WHEN** die API-URL nicht erreichbar ist oder ein Timeout auftritt
- **THEN** zeigt das System eine Fehlermeldung ohne Karten zu importieren

#### Scenario: API gibt kein valides JSON zurück
- **WHEN** die API-URL valides JSON zurückgibt, das aber kein Array von Karten ist
- **THEN** zeigt das System eine Fehlermeldung ohne Karten zu importieren

### Requirement: Optionaler Farbfilter beim API-Import
Das System SHALL beim API-Import denselben Farbfilter wie beim File-Upload anbieten.

#### Scenario: Import mit Farbfilter
- **WHEN** der User einen oder mehrere Farben auswählt und den API-Import startet
- **THEN** importiert das System nur Karten mit den ausgewählten Farben

#### Scenario: Import ohne Farbfilter
- **WHEN** der User keine Farbe auswählt
- **THEN** importiert das System alle Karten der API-Antwort
