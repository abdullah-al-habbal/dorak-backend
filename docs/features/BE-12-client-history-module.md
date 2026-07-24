# BE-12 — ClientHistory Module (PRD Phase 2)

## Status: ✅ Complete
## Frontend Consumer: ClientHistoryScreen (pending FE-03)

## What Was Built
- 2 Models: ClientServiceHistoryModel, ClientServiceHistoryMediaModel
- 1 Enum: HistoryMediaType (before/after/reference)
- 1 ValueObject: ServiceHistoryMetadataValueObject (productsUsed, lengthSettings, colorCodes)
- 1 Cast: ServiceHistoryMetadataCast
- 3 Commands: CreateClientServiceHistoryCommand, AttachHistoryMediaCommand, RebookFromHistoryCommand
- 1 Query: ListClientServiceHistoryQuery
- 4 Resolvers + 4 Handlers following Action→Handler→EloquentResolver pattern
- BookingCompletedObserver — auto-creates history record on booking completion

## API Endpoints
| Method | Endpoint | Auth | Action |
|--------|----------|------|--------|
| GET | `/api/v1/client/history` | auth:client | `ListClientServiceHistoryAction` |
| POST | `/api/v1/client/history/{history}/media` | auth:client | `AttachHistoryMediaAction` |
| POST | `/api/v1/client/history/{history}/rebook` | auth:client | `RebookFromHistoryAction` |

## Response Schemas

### GET /api/v1/client/history → 200 (paginated)
```json
{
  "data": [
    {
      "id": "uuid",
      "client_id": "uuid",
      "booking_id": "uuid",
      "barber_id": "uuid",
      "branch_id": "uuid",
      "offered_service_id": "uuid",
      "catalog_item_id": "uuid",
      "performed_at": "2026-07-20T14:00:00Z",
      "client_rating": 5,
      "client_notes": "Great fade",
      "barber_notes": null,
      "metadata": {
        "productsUsed": ["Pomade X"],
        "lengthSettings": { "top": "2cm", "sides": "0.5cm" },
        "colorCodes": []
      },
      "barber": { "id": "uuid", "name": "Ahmad" },
      "branch": { "id": "uuid", "name": "Dorak Salon" },
      "catalog_item": {
        "id": "uuid",
        "name": { "en": "Fade Haircut", "ar": "قصة fade" }
      },
      "media": [
        {
          "id": "uuid",
          "photo_url": "https://...",
          "photo_type": "before|after|reference",
          "uploaded_at": "2026-07-20T14:30:00Z"
        }
      ],
      "created_at": "2026-07-20T14:00:00Z",
      "updated_at": "2026-07-20T14:30:00Z"
    }
  ],
  "meta": { "pagination": { "..." } }
}
```

### POST /api/v1/client/history/{history}/media → 201
Multipart upload — accepts `photo` file + `photo_type` (before|after|reference).

### POST /api/v1/client/history/{history}/rebook → 201
Returns new BookingResource shape (see BE-04).

## Tests: Included in 34 dedicated feature tests across 6 modules
