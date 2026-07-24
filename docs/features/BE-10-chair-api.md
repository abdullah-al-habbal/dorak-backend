# BE-10 — Chair API

## Status: ✅ Complete
## Frontend Consumer: ChairDetailScreen, ChairListScreen

## What Was Built
- List/Show/Update chairs + Filament CRUD pages

## API Endpoints
| Method | Endpoint | Auth | Action |
|--------|----------|------|--------|
| GET | `/api/v1/chairs` | — | `ListChairsAction` |
| GET | `/api/v1/chairs/{chair}` | — | `ShowChairAction` |
| GET | `/api/v1/branches/{branch}/chairs` | — | `ListChairsAction` |
| PATCH | `/api/v1/chairs/{chair}` | auth:client | `UpdateChairAction` |

## Response Schemas

### GET /api/v1/chairs/{chair} → 200
```json
{
  "data": {
    "id": "uuid",
    "label": "Chair 1",
    "status": "available|occupied|maintenance",
    "ui_metadata": {
      "position_x": 100,
      "position_y": 200,
      "width": 60,
      "height": 60,
      "rotation": 0,
      "shape": "square|circle"
    },
    "branch_id": "uuid",
    "barber": {
      "id": "uuid",
      "name": "string",
      "email": "string",
      "is_freelancer": false,
      "status": "active|inactive",
      "latitude": null,
      "longitude": null,
      "distance": null,
      "compatibility_score": null,
      "rank": null,
      "created_at": "datetime"
    },
    "created_at": "datetime"
  }
}
```

### PATCH /api/v1/chairs/{chair} → 200
Body: `{ "label": "string", "status": "available|occupied|maintenance", "barber_id": "uuid|null" }`

## Tests: 5 tests (status update, label update, barber assignment, invalid status 422, unauthorized 401)
