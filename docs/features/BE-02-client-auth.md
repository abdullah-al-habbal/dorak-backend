# BE-02 — Client Auth Module

## Status: ✅ Complete
## Frontend Consumer: Auth screens (LoginScreen, RegisterScreen)

## What Was Built
- Login, Register, Logout, RefreshToken, UpdateUniversePreference, SocialLogin
- `ClientModel` — fillable: name, email, password, preferred_universe. Sanctum HasApiTokens, HasUuids, HasTranslations

## API Endpoints
| Method | Endpoint | Auth | Action |
|--------|----------|------|--------|
| POST | `/api/v1/client/login` | — | `LoginAction` |
| POST | `/api/v1/client/register` | — | `RegisterAction` |
| POST | `/api/v1/client/social/{provider}` | — | `SocialLoginAction` |
| POST | `/api/v1/client/logout` | auth:client | `LogoutAction` |
| POST | `/api/v1/client/refresh-token` | auth:client | `RefreshTokenAction` |
| PATCH | `/api/v1/client/preferences/universe` | auth:client | `UpdateUniversePreferenceAction` |

## Response Schemas

### POST /api/v1/client/login → 200
```json
{
  "success": true,
  "statusCode": 200,
  "data": {
    "client": {
      "id": "uuid",
      "name": "string",
      "email": "string",
      "preferred_universe": "men|women",
      "phone": "string|null",
      "avatar": "string|null",
      "email_verified_at": "datetime|null",
      "created_at": "datetime"
    },
    "token": "string"
  }
}
```

### POST /api/v1/client/register → 201
```json
{
  "success": true,
  "statusCode": 201,
  "data": {
    "client": { "..." },
    "token": "string"
  }
}
```

### POST /api/v1/client/preferences/universe → 200
```json
{
  "success": true,
  "statusCode": 200,
  "data": {
    "preferred_universe": "men|women"
  }
}
```

## Tests: 6 contract tests (login, register, logout, refresh-token, profile, universe preference)
