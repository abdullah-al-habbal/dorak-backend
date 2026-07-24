# BE-05 — Review API

## Status: ✅ Complete
## Frontend Consumer: WriteReviewScreen, BranchShowPage

## What Was Built
- `SubmitReviewAction` — validates booking ownership + completed status + no duplicate
- `GetBranchReviewsAction` — paginated reviews for a branch

## API Endpoints
| Method | Endpoint | Auth | Action |
|--------|----------|------|--------|
| POST | `/api/v1/client/bookings/{booking}/review` | auth:client | `SubmitReviewAction` |
| GET | `/api/v1/branches/{branch}/reviews` | — | `GetBranchReviewsAction` |

## Response Schemas

### GET /api/v1/branches/{branch}/reviews → 200 (paginated)
```json
{
  "data": [
    {
      "id": "uuid",
      "rating": 5,
      "comment": "Great haircut!",
      "author_name": "Ahmad",
      "created_at": "2026-07-24T12:00:00Z"
    }
  ],
  "meta": { "pagination": { "..." } }
}
```

### POST /api/v1/client/bookings/{booking}/review → 201
```json
{
  "data": {
    "id": "uuid",
    "rating": 5,
    "comment": "Great haircut!",
    "author_name": "Ahmad",
    "created_at": "2026-07-24T12:00:00Z"
  }
}
```

## Tests: Included in contract test suite
