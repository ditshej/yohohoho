## Context

The existing import flow runs through `CardsImportController` → `CardImportService`. The service expects an array of card data and is API-agnostic — it can be reused directly. The new flow only replaces the data source (HTTP instead of upload).

## Goals / Non-Goals

**Goals:**
- HTTP fetch from `https://op-cards.ditshej.ch/api/cards` via Laravel HTTP Client
- Reuse `CardImportService` without changes
- Tab-based UI in the existing form (File Upload | API Import)

**Non-Goals:**
- Cron-based synchronization
- API authentication
- Multiple configurable API endpoints

## Decisions

**Laravel HTTP Client instead of cURL/Guzzle directly**
Laravel's `Http::get()` is already available, well-testable (fake/mock), and adds no new dependency.

**URL pre-filled but editable**
The API URL is set as a default value in the form, not hardcoded in the backend. This allows testing against other endpoints without code changes.

**New route + new Form Request, new controller action**
`storeFromApi()` stays separate from `store()` (file upload). No mixing of the two flows — simpler tests, clear responsibilities.

**No changes to CardImportService**
The service already accepts an array. The HTTP response array is passed directly.

## Risks / Trade-offs

**[Risk] API unreachable at runtime** → Set HTTP timeout (5s), show error to user as validation error (same as file upload).

**[Risk] API format changes** → Since the format is identical to the existing import, the error would only occur during CardImportService processing — already handled there.

## Open Questions

- Should the color filter also be presented as a checkbox list for API import (like file upload) or as a simple text field?
  → Assumption: same checkbox list as file upload.
