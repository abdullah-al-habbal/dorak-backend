# Feature Index — Dorak Backend

> **Append entry after adding each feature.** This is the chronological map of every feature built.

---

### Core
- `HealthCheckAction` [GET /api/v1/health]
- `ApiResponseBodyValueObject` — standard response shape `{success, statusCode, code, message, timestamp, data, meta?, errors?}`
- `BaseApiAction` + `ApiResponseTrait` — paginated/ok/created/error/validation helpers
- `BaseModuleServiceProvider` — auto-mounts `Routes/Api/V1/*.php` at `/api/v1` with `['api']` middleware
- Auth guards: `client` (sanctum), `barber` (sanctum), `barber_dashboard` (session), `branch` (session), `admin` (session)

### Client
- `LoginAction` [POST /api/v1/client/login] — email/password → Sanctum `client-app` token + client profile
- `RegisterAction` [POST /api/v1/client/register] — name/email/password → token + client (201)
- `LogoutAction` [POST /api/v1/client/logout] — revoke `currentAccessToken()` (auth:client)
- `RefreshTokenAction` [POST /api/v1/client/refresh-token] — rotate token (auth:client)
- `UpdateUniversePreferenceAction` [PATCH /api/v1/client/preferences/universe] — persists `preferred_universe` (men/women/neutral) (auth:client)
- `ClientModel` — fillable: name, email, password, preferred_universe. Sanctum HasApiTokens, HasUuids, HasTranslations

### Explore
- `ExploreBranchesAction` [GET /api/v1/explore/branches] — Haversine SQL radius search with `lat`, `lng`, `radius`, `universe` (via brand) filters. Paginated.
- `ExploreBarbersAction` [GET /api/v1/explore/barbers] — Haversine radius search for `is_freelancer=true` barbers. Paginated.
- `BranchResource` — id, name, email, status, latitude, longitude, brand_id, distance?, created_at
- `BarberResource` — id, name, email, is_freelancer, status, latitude, longitude, distance?, created_at
- `BranchModel` — fillable: name, email, password, brand_id, latitude, longitude. `BranchModel::brand()` BelongsTo BrandModel
- `BarberModel` — fillable: name, email, password. `BarberModel::services()` morphMany
- `BrandModel` — fillable: owner_id, name, description, logo, base_currency_id. `universe` field (default neutral)
- Migration: branches.brand_id FK + lat/lng, barbers.lat/lng, brands.universe, clients.preferred_universe

### Models
- `ClientModel` — HasUuids, HasTranslations, HasFactory, Notifiable, Sanctum tokens. `bans()` morphMany
- `BarberModel` — `activationLogs()`, `bans()`, `services()`. Accessor: `is_enabled`
- `BranchModel` — `activationLogs()`, `brand()`, accessor `is_enabled`
- `BrandModel` — `owner()` (→ClientModel), `baseCurrency()`, `branches()`, `affiliations()`, `services()`, `preference()`
- `PreferenceModel` — polymorphic `preferenceable()` (MorphTo). Fields: preferred_language, notification_enabled, display_currency_id, theme, price_display_mode
- `BanModel` — polymorphic `bannable()` (MorphTo). Scope: `active` (banned_from ≤ now AND banned_until null OR > now)

### CI / Code Quality
- `phpstan.neon` — level `max`, paths: modules, bootstrap, config, routes. Larastan extension
- `.github/workflows/backend-ci.yml` — Pint formatting, PHPStan max, PHPUnit coverage, SonarQube scan (MySQL service)
- `sonar-project.properties` — backend sources, exclusions, coverage reports
