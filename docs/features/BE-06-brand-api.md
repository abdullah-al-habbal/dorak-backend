# BE-06 — Brand API

## Status: ✅ Complete
## Frontend Consumer: BrandListScreen, BrandDetailScreen, BrandFormScreen

## What Was Built
- `ListBrandsAction` — list all brands (no auth)
- `ShowBrandAction` — single brand detail (no auth)
- `CreateBrandAction` — create brand (auth:client)
- `UpdateBrandAction` — update brand (auth:client)

## API Endpoints
| Method | Endpoint | Auth | Action |
|--------|----------|------|--------|
| GET | `/api/v1/brands` | — | `ListBrandsAction` |
| GET | `/api/v1/brands/{brand}` | — | `ShowBrandAction` |
| POST | `/api/v1/brands` | auth:client | `CreateBrandAction` |
| PUT | `/api/v1/brands/{brand}` | auth:client | `UpdateBrandAction` |

## Response Schemas

### GET /api/v1/brands → 200 (paginated)
```json
{
  "data": [
    {
      "id": "uuid",
      "name": { "en": "string", "ar": "string" },
      "description": { "en": "string", "ar": "string" },
      "logo": "string|null",
      "owner": { "id": "uuid", "email": "string" },
      "base_currency": { "id": "uuid", "code": "SYP" },
      "branches_count": 3,
      "created_at": "datetime"
    }
  ],
  "meta": { "pagination": { "..." } }
}
```

### GET /api/v1/brands/{brand} → 200 (same shape with `branches` relation loaded)

## Tests: Included in contract test suite + Brand Filament list page load test
