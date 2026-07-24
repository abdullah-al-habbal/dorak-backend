# BE-14 — ClientInteraction Module (PRD Phase 4)

## Status: ✅ Complete
## Frontend Consumer: ExploreScreen, ProfileScreen (pending FE-05, FE-06)

## What Was Built
- 3 Models: ClientFavoriteModel (polymorphic), ClientSavedFilterModel, ClientDiscoveryPreferenceModel
- 1 Enum: InteractionTypeEnum
- 2 ValueObjects: FilterConfigurationValueObject, InteractionContextValueObject
- Full CRUD for favorites (toggle), saved filters, discovery preferences
- ClientInteractionServiceProvider registered in bootstrap/providers.php

## API Endpoints
| Method | Endpoint | Auth | Action |
|--------|----------|------|--------|
| GET | `/api/v1/client/favorites` | auth:client | `ListFavoritesAction` |
| POST | `/api/v1/client/favorites` | auth:client | `AddFavoriteAction` |
| DELETE | `/api/v1/client/favorites/{favorite}` | auth:client | `RemoveFavoriteAction` |
| GET | `/api/v1/client/saved-filters` | auth:client | `ListSavedFiltersAction` |
| POST | `/api/v1/client/saved-filters` | auth:client | `CreateSavedFilterAction` |
| GET | `/api/v1/client/saved-filters/{filter}` | auth:client | `ShowSavedFilterAction` |
| PUT | `/api/v1/client/saved-filters/{filter}` | auth:client | `UpdateSavedFilterAction` |
| DELETE | `/api/v1/client/saved-filters/{filter}` | auth:client | `DeleteSavedFilterAction` |
| GET | `/api/v1/client/discovery-preferences` | auth:client | `GetDiscoveryPreferenceAction` |
| PATCH | `/api/v1/client/discovery-preferences` | auth:client | `UpdateDiscoveryPreferenceAction` |

## Response Schemas

### GET /api/v1/client/favorites → 200
```json
{
  "data": [
    {
      "id": "uuid",
      "favorable_id": "uuid",
      "favorable_type": "App\\Models\\Branch|App\\Models\\Barber|App\\Models\\Brand",
      "favorable": { "..." },
      "created_at": "2026-07-24T12:00:00Z"
    }
  ]
}
```

### GET /api/v1/client/saved-filters → 200
```json
{
  "data": [
    {
      "id": "uuid",
      "name": "My Favorites",
      "filter_config": {
        "universe": "men",
        "radius": 5.0,
        "catalog_item_ids": [1, 2],
        "price_range": { "min": 1000, "max": 10000 }
      },
      "created_at": "2026-07-24T12:00:00Z",
      "updated_at": "2026-07-24T12:00:00Z"
    }
  ]
}
```

### GET /api/v1/client/discovery-preferences → 200
```json
{
  "data": {
    "id": "uuid",
    "preferred_universe": "men",
    "default_radius": 10.0,
    "hidden_brand_ids": ["uuid1"],
    "show_unavailable": false,
    "created_at": "2026-07-24T12:00:00Z",
    "updated_at": "2026-07-24T12:00:00Z"
  }
}
```

## Tests: 15 contract tests in ApiResponseContractTest
