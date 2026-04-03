## Context

Der bestehende Import-Flow läuft über `CardsImportController` → `CardImportService`. Der Service erwartet ein Array von Karten-Daten und ist API-agnostisch — er kann direkt wiederverwendet werden. Der neue Flow ersetzt nur die Datenquelle (HTTP statt Upload).

## Goals / Non-Goals

**Goals:**
- HTTP-Abruf von `https://op-cards.ditshej.ch/api/cards` per Laravel HTTP Client
- Wiederverwendung von `CardImportService` ohne Änderungen
- Tab-basierte UI im bestehenden Formular (File Upload | API Import)

**Non-Goals:**
- Cron-basierte Synchronisation
- API-Authentifizierung
- Mehrere konfigurierbare API-Endpoints

## Decisions

**Laravel HTTP Client statt cURL/Guzzle direkt**
Laravel's `Http::get()` ist bereits verfügbar, gut testbar (fake/mock), und fügt keine neue Dependency hinzu.

**URL vorausgefüllt, aber editierbar**
Die API-URL wird als Default-Wert im Formular gesetzt, nicht hardcoded im Backend. Das erlaubt Tests gegen andere Endpoints ohne Code-Änderung.

**Neue Route + neue Form Request, neue Controller-Action**
`storeFromApi()` bleibt getrennt von `store()` (File-Upload). Keine Vermischung der zwei Flows — einfachere Tests, klare Verantwortlichkeiten.

**Keine Änderung am CardImportService**
Der Service nimmt bereits ein Array entgegen. Das HTTP-Response-Array wird direkt übergeben.

## Risks / Trade-offs

**[Risiko] API nicht erreichbar zur Laufzeit** → HTTP-Timeout setzen (5s), Fehler als Validation-Error dem User anzeigen (wie beim File-Upload).

**[Risiko] API-Format ändert sich** → Da Format identisch zum bestehenden Import ist, tritt der Fehler erst bei der CardImportService-Verarbeitung auf — dort bereits behandelt.

## Open Questions

- Soll der Farbfilter auch beim API-Import als Checkbox-Liste dargestellt werden (wie beim File-Upload) oder als einfaches Textfeld?
  → Annahme: gleiche Checkbox-Liste wie beim File-Upload.
