# BE-07 — Barber Affiliation API

## Status: ✅ Complete
## Frontend Consumer: AffiliationListScreen, AffiliationInviteScreen

## What Was Built
- Create, Accept, Reject affiliations + List barber's affiliations

## API Endpoints
| Method | Endpoint | Auth | Action |
|--------|----------|------|--------|
| POST | `/api/v1/barbers/{barber}/affiliate` | auth:barber | `CreateAffiliationAction` |
| POST | `/api/v1/affiliations/{affiliation}/accept` | auth:barber | `AcceptAffiliationAction` |
| POST | `/api/v1/affiliations/{affiliation}/reject` | auth:barber | `RejectAffiliationAction` |
| GET | `/api/v1/barbers/{barber}/affiliations` | auth:barber | `ListBarberAffiliationsAction` |

## Response Schemas

### GET /api/v1/barbers/{barber}/affiliations → 200
```json
{
  "data": [
    {
      "id": "uuid",
      "barber_id": "uuid",
      "affiliable_id": "uuid",
      "affiliable_type": "Modules\\\\Brand\\\\Models\\\\BrandModel|Modules\\\\Branch\\\\Models\\\\BranchModel",
      "status": "pending|active|terminated",
      "commission_rate": null,
      "invited_at": "2026-07-24T12:00:00Z",
      "accepted_at": null,
      "terminated_at": null,
      "created_at": "datetime"
    }
  ]
}
```

## Tests: Multi-shop constraint test + contract tests
