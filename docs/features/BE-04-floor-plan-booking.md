# BE-04 — Floor Plan & Booking Engine

## Status: ✅ Complete
## Frontend Consumer: FloorPlanScreen, BookingScreen, BranchShowPage

## What Was Built
- `GetFloorPlanAction` — returns chairs with barber info
- `CreateBookingAction` — double-booking check (409), at-home booking support
- `ListUserBookingsAction` — auth client's paginated bookings
- `ShowBookingAction` — single booking detail
- `CancelBookingAction` — cancel confirmed booking
- `BookingCompletedObserver` — auto-creates history record on completion

## API Endpoints
| Method | Endpoint | Auth | Action |
|--------|----------|------|--------|
| GET | `/api/v1/branches/{branch}/floor-plan` | — | `GetFloorPlanAction` |
| POST | `/api/v1/bookings` | auth:client | `CreateBookingAction` |
| GET | `/api/v1/bookings` | auth:client | `ListUserBookingsAction` |
| GET | `/api/v1/bookings/{booking}` | auth:client | `ShowBookingAction` |
| POST | `/api/v1/client/bookings/{booking}/cancel` | auth:client | `CancelBookingAction` |

## Response Schemas

### GET /api/v1/branches/{branch}/floor-plan → 200
```json
{
  "data": {
    "chairs": [
      {
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
        }
      }
    ]
  }
}
```

### POST /api/v1/bookings → 201
```json
{
  "data": {
    "id": "uuid",
    "time_slot": "2026-07-25T14:00:00Z",
    "status": "booked",
    "chair": { "id": "uuid", "label": "Chair 1", "status": "occupied", "ui_metadata": {}, "barber": null },
    "barber": { "..." },
    "services": [
      { "id": "uuid", "name": "Fade Haircut", "description": "...", "price": 5000.0, "currency_id": "uuid", "duration": 30, "at_home": false, "active": true, "created_at": "datetime" }
    ],
    "at_home_location": null,
    "created_at": "datetime"
  }
}
```

### POST /api/v1/client/bookings/{booking}/cancel → 200
```json
{
  "data": {
    "id": "uuid",
    "status": "canceled",
    "..."
  }
}
```

### Error: Double booking → 409
```json
{
  "success": false,
  "statusCode": 409,
  "code": "CONFLICT",
  "message": "This seat was just taken."
}
```

## Tests: 3 concurrency tests + 5 booking contract tests
