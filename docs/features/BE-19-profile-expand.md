# BE-19 — Profile Expand (Phone, Avatar, Soft Delete, Password Mgmt)

## Status: ✅ Complete
## Frontend Consumer: EditProfileScreen, ForgotPasswordScreen, ResetPasswordScreen, VerifyEmailScreen, ChangePasswordScreen

## What Was Built
- **ClientModel**: added `phone` (nullable string), `avatar` (nullable string), `status` (string, default `enabled`), `SoftDeletes` trait
- **ClientStatusEnum**: `pending`, `enabled`, `disabled`
- **UpdateProfileAction**: accepts `phone` field alongside `name`, `email`, `password`
- **UploadAvatarAction**: multipart upload to `public/avatars`, returns public URL, deletes old avatar
- **DeleteAccountAction**: password confirmation required, blocks if active bookings exist, soft-deletes
- **SendEmailVerificationAction**: sends 6-digit code via mailable, 10-min TTL in cache
- **VerifyEmailAction**: validates cached 6-digit code, sets `email_verified_at`
- **ForgotPasswordAction**: sends 6-digit reset code via mailable, 10-min TTL (no auth required)
- **ResetPasswordAction**: validates code + new password, resets password, revokes all tokens (no auth required)
- **ChangePasswordAction**: validates `current_password` via `current_password:client` rule, updates, revokes all tokens

## API Endpoints
| Method | Endpoint | Auth | Action |
|--------|----------|------|--------|
| PATCH | `/api/v1/client/profile` | client | `UpdateProfileAction` |
| POST | `/api/v1/client/avatar` | client | `UploadAvatarAction` |
| DELETE | `/api/v1/client/account` | client | `DeleteAccountAction` |
| POST | `/api/v1/client/email/verify/send` | client | `SendEmailVerificationAction` |
| POST | `/api/v1/client/email/verify` | client | `VerifyEmailAction` |
| POST | `/api/v1/client/forgot-password` | — | `ForgotPasswordAction` |
| POST | `/api/v1/client/reset-password` | — | `ResetPasswordAction` |
| PATCH | `/api/v1/client/password` | client | `ChangePasswordAction` |

## Response Schemas

### PATCH /api/v1/client/profile
**Request:**
| Field | Type | Required | Notes |
|-------|------|----------|-------|
| `name` | string | no | max: 255 |
| `email` | string | no | unique (ignored for current user) |
| `password` | string | no | min: 8, must have `password_confirmation` |
| `phone` | string | no | max: 20 |

**Response:**
```json
{
  "success": true,
  "statusCode": 200,
  "code": "SUCCESS",
  "message": "core::messages.success",
  "timestamp": "2026-07-24T12:00:00Z",
  "data": {
    "id": "uuid",
    "name": {"en": "User Name", "ar": "اسم المستخدم"},
    "email": "user@example.com",
    "phone": "+966501234567",
    "preferred_universe": "men|women"
  },
  "errors": null
}
```

### POST /api/v1/client/avatar
**Request:** `multipart/form-data`
| Field | Type | Required | Notes |
|-------|------|----------|-------|
| `avatar` | file | yes | image, mimes: jpeg,png,jpg, max: 2048KB |

**Response:**
```json
{
  "success": true,
  "statusCode": 200,
  "code": "SUCCESS",
  "message": "core::messages.success",
  "timestamp": "2026-07-24T12:00:00Z",
  "data": {
    "avatar_url": "https://example.com/storage/avatars/abc123.jpg"
  },
  "errors": null
}
```

### DELETE /api/v1/client/account
**Request:**
| Field | Type | Required | Notes |
|-------|------|----------|-------|
| `password` | string | yes | current password for confirmation |

**Response (success):**
```json
{
  "success": true,
  "statusCode": 200,
  "code": "SUCCESS",
  "message": "core::messages.success",
  "timestamp": "2026-07-24T12:00:00Z",
  "data": null,
  "errors": null
}
```
**Response (invalid password):** 401 — `"core::messages.invalid_credentials"`
**Response (active bookings):** 422 — `"core::messages.active_bookings_block_deletion"`

### POST /api/v1/client/email/verify/send
No request body (uses authenticated user's email).

**Response:**
```json
{
  "success": true,
  "statusCode": 200,
  "code": "SUCCESS",
  "message": "core::messages.verification_code_sent",
  "timestamp": "2026-07-24T12:00:00Z",
  "data": null,
  "errors": null
}
```
**Response (already verified):** 200 — `"core::messages.email_already_verified"`

### POST /api/v1/client/email/verify
**Request:**
| Field | Type | Required | Notes |
|-------|------|----------|-------|
| `code` | string | yes | exactly 6 characters |

**Response:**
```json
{
  "success": true,
  "statusCode": 200,
  "code": "SUCCESS",
  "message": "core::messages.email_verified",
  "timestamp": "2026-07-24T12:00:00Z",
  "data": null,
  "errors": null
}
```
**Response (invalid code):** 422 — `"core::messages.invalid_verification_code"`
**Response (already verified):** 200 — `"core::messages.email_already_verified"`

### POST /api/v1/client/forgot-password
**Request:**
| Field | Type | Required | Notes |
|-------|------|----------|-------|
| `email` | string | yes | must exist in `clients` table |

**Response:**
```json
{
  "success": true,
  "statusCode": 200,
  "code": "SUCCESS",
  "message": "core::messages.reset_code_sent",
  "timestamp": "2026-07-24T12:00:00Z",
  "data": null,
  "errors": null
}
```

### POST /api/v1/client/reset-password
**Request:**
| Field | Type | Required | Notes |
|-------|------|----------|-------|
| `email` | string | yes | must exist in `clients` table |
| `code` | string | yes | exactly 6 characters |
| `password` | string | yes | min: 8, must have `password_confirmation` |

**Response:**
```json
{
  "success": true,
  "statusCode": 200,
  "code": "SUCCESS",
  "message": "core::messages.password_reset",
  "timestamp": "2026-07-24T12:00:00Z",
  "data": null,
  "errors": null
}
```
**Response (invalid code):** 422 — `"core::messages.invalid_reset_code"`

### PATCH /api/v1/client/password
**Request:**
| Field | Type | Required | Notes |
|-------|------|----------|-------|
| `current_password` | string | yes | validated via `current_password:client` rule |
| `password` | string | yes | min: 8, must have `password_confirmation` |

**Response:**
```json
{
  "success": true,
  "statusCode": 200,
  "code": "SUCCESS",
  "message": "core::messages.password_changed",
  "timestamp": "2026-07-24T12:00:00Z",
  "data": null,
  "errors": null
}
```

## Client Database Schema
```sql
-- Base (migration 000000)
clients:
  id              uuid primary key
  name            json
  email           string unique
  email_verified_at  timestamp nullable
  password        string
  remember_token  string
  preferred_universe  string(10) default 'men'
  created_at      timestamp
  updated_at      timestamp

-- Added (migration 200002)
  phone           string nullable
  avatar          string nullable
  status          string(20) default 'enabled'
  deleted_at      timestamp (soft deletes)
```

## Tests: 16 contract tests (5 account deletion + 4 email verification + 4 forgot/reset password + 3 password change)
