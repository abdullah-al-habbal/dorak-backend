# BE-01 — Core Infrastructure

## Status: ✅ Complete
## Frontend Consumer: — (infrastructure layer)

## What Was Built
- `HealthCheckAction` — `GET /api/v1/health`
- `ApiResponseBodyValueObject` — standard response envelope: `{success, statusCode, code, message, timestamp, data, meta?, errors?}`
- `BaseApiAction` + `ApiResponseTrait` — paginated/ok/created/error/validation helpers
- `BaseModuleServiceProvider` — auto-mounts `Routes/Api/V1/*.php` at `/api/v1` with `['api']` middleware
- Auth guards: `client` (sanctum), `barber` (sanctum), `barber_dashboard` (session), `branch` (session), `admin` (session)

## API Endpoints
| Method | Endpoint | Auth | Action |
|--------|----------|------|--------|
| GET | `/api/v1/health` | — | `HealthCheckAction` |

## Response Envelope (all endpoints)
```json
{
  "success": true,
  "statusCode": 200,
  "code": "SUCCESS",
  "message": "core::messages.success",
  "timestamp": "2026-07-24T12:00:00Z",
  "data": {},
  "meta": { "pagination": {} },
  "errors": null
}
```

## Tests: Core health check + envelope tests
