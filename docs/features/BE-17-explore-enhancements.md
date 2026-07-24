# BE-17 — Explore Enhancements (Phase 5 Filters + Ranking)

## Status: ✅ Complete
## Frontend Consumer: ExploreScreen (pending)

## What Was Built
- Phase 5 filter extensions on Explore endpoints: `catalog_item_ids[]`, `available_now`, `price_range[min/max]`, `rating_min`, `face_shape_compatible`
- Composite ranking formula: `geo*geographic + vector_similarity*α + edge_boost*β + face_match*γ`
- `compatibility_score` + `rank` fields appended to `BranchResource` and `BarberResource` responses (null when unauthenticated)
- Default weights: α=0.4, β=0.3, γ=0.1, geographic=0.2 (via `RecommendationFactorWeightsValueObject`)
- Geographic scoring: `1 / (1 + distance / radius)`
- Cosine similarity computed between client preference vector and entity embeddings from `ClientPreferenceVectorModel` / `EntityEmbeddingModel`
- Face-shape matching via `whereJsonContains('face_shapes', value)` on catalog items through offered services
- Edge boost from `RecommendationEdgeModel` (source_type=client, target_type=branch|barber)

## API Endpoints
| Method | Endpoint | Auth | Action |
|--------|----------|------|--------|
| GET | `/api/v1/explore/branches` | optional (sanctum) | `ExploreBranchesAction` |
| GET | `/api/v1/explore/barbers` | optional (sanctum) | `ExploreBarbersAction` |

### GET /api/v1/explore/branches

**Request Query Parameters:**
| Parameter | Type | Required | Notes |
|-----------|------|----------|-------|
| `lat` | float | yes | between -90, 90 |
| `lng` | float | yes | between -180, 180 |
| `radius` | float | yes | km, min: 0 |
| `universe` | string | yes | `UniverseEnum` value |
| `per_page` | integer | no | 1–100, default: 20 |
| `catalog_item_ids[]` | array | no | integers, exists in `service_catalog_items` |
| `available_now` | boolean | no | filters branches with unbooked chairs |
| `price_range.min` | numeric | no | min: 0 |
| `price_range.max` | numeric | no | min: 0 |
| `rating_min` | numeric | no | 0–5, filters by avg review rating |
| `face_shape_compatible` | string | no | matches catalog items with matching face_shapes JSON |

**Response (authenticated — with ranking):**
```json
{
  "success": true,
  "statusCode": 200,
  "code": "SUCCESS",
  "message": "core::messages.success",
  "timestamp": "2026-07-24T12:00:00Z",
  "data": [
    {
      "id": "uuid",
      "name": {"en": "Branch Name", "ar": "اسم الفرع"},
      "email": "branch@example.com",
      "status": "active",
      "latitude": 24.7136,
      "longitude": 46.6753,
      "brand_id": "uuid",
      "distance": 1.23,
      "compatibility_score": 0.87,
      "rank": 1,
      "created_at": "2026-07-24T12:00:00Z"
    }
  ],
  "meta": {
    "pagination": {
      "total": 42,
      "per_page": 20,
      "current_page": 1,
      "last_page": 3
    }
  },
  "errors": null
}
```

**Response (unauthenticated — no ranking):**
```json
{
  "data": [
    {
      "id": "uuid",
      "name": {"en": "Branch Name", "ar": "اسم الفرع"},
      "email": "branch@example.com",
      "status": "active",
      "latitude": 24.7136,
      "longitude": 46.6753,
      "brand_id": "uuid",
      "distance": 1.23,
      "compatibility_score": null,
      "rank": null,
      "created_at": "2026-07-24T12:00:00Z"
    }
  ]
}
```

### GET /api/v1/explore/barbers

**Request Query Parameters:**
| Parameter | Type | Required | Notes |
|-----------|------|----------|-------|
| `lat` | float | yes | between -90, 90 |
| `lng` | float | yes | between -180, 180 |
| `radius` | float | yes | km, min: 0 |
| `per_page` | integer | no | 1–100, default: 20 |
| `catalog_item_ids[]` | array | no | integers, exists in `service_catalog_items` |
| `available_now` | boolean | no | filters barbers without active bookings |
| `price_range.min` | numeric | no | min: 0 |
| `price_range.max` | numeric | no | min: 0 |
| `rating_min` | numeric | no | 0–5, filters by avg review rating |
| `face_shape_compatible` | string | no | matches catalog items with matching face_shapes JSON |

**Response (authenticated — with ranking):**
```json
{
  "success": true,
  "statusCode": 200,
  "code": "SUCCESS",
  "message": "core::messages.success",
  "timestamp": "2026-07-24T12:00:00Z",
  "data": [
    {
      "id": "uuid",
      "name": {"en": "Barber Name", "ar": "اسم الحلاق"},
      "email": "barber@example.com",
      "is_freelancer": true,
      "status": "active",
      "latitude": 24.7136,
      "longitude": 46.6753,
      "distance": 0.95,
      "compatibility_score": 0.72,
      "rank": 1,
      "created_at": "2026-07-24T12:00:00Z"
    }
  ],
  "meta": {
    "pagination": {
      "total": 18,
      "per_page": 20,
      "current_page": 1,
      "last_page": 1
    }
  },
  "errors": null
}
```

## Tests: 8 contract tests extending explore endpoint assertions + 34 dedicated feature tests across 6 modules (OfferedService, Ban, Client, ClientFaceProfile, ClientInteraction, ClientRecommendation)
