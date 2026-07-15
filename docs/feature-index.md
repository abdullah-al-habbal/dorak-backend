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

### Floor Plan & Booking Engine
- `GetFloorPlanAction` [GET /api/v1/branches/{branch}/floor-plan] — returns chairs with barber, no auth
- `ChairResource` — id, label, status, ui_metadata, barber (via BarberResource::make)
- `BranchModel.chairs()` — HasMany to ChairModel
- `CreateBookingAction` [POST /api/v1/bookings] — accepts CreateBookingRequest (FormRequest), builds CreateBookingCommand, double-booking check (409), at-home booking support, returns BookingResource (201)
- `ListUserBookingsAction` [GET /api/v1/bookings] — auth client's paginated bookings
- `BookingResource` — id, time_slot, status, chair (ChairResource::make), barber (BarberResource::make), services (ServiceResource::collection), at_home_location
- `ServiceResource` — id, name, price
- `CreateBookingRequest` — extends BaseApiFormRequest, validates chair_id/barber_id/time_slot/service_ids. Mutually exclusive validation: chair_id XOR at_home_location. at_home_location.lat/lng with required_with/prohibited_with
- `CreateBookingCommand` — readonly value object with PascalCase properties (ChairId, BarberId, ClientId, TimeSlot, ServiceIds, AtHomeLocation). ChairId nullable
- `BookingModel` — fillable: client_id, chair_id, barber_id, time_slot, status, at_home_location. HasUuids, HasFactory. at_home_location cast to array
- Routes: bookings.php (auth:client), floor-plan.php (no auth)

### CI / Code Quality
- `phpstan.neon` — level `max`, paths: modules/, bootstrap/. Larastan extension
- `.github/workflows/backend-ci.yml` — Pint formatting, PHPStan max, PHPUnit coverage, SonarQube scan (MySQL service)
- `sonar-project.properties` — backend sources, exclusions, coverage reports
- BaseApiAction: removed abstract __invoke() (signatures vary per action)

### Branch/Barber Detail
- `GetBranchDetailAction` [GET /api/v1/explore/branches/{branch}] — branch info + chairs_count + barbers (nested BarberResource) + services (nested ServiceResource)
- `GetBarberDetailAction` [GET /api/v1/explore/barbers/{barber}] — barber info + services (nested ServiceResource)
- Routes added to explore.php

### Review API
- `SubmitReviewAction` [POST /api/v1/client/bookings/{booking}/review] — validates booking ownership + completed status + no duplicate; derives subject_type/subject_id from chair.branch or barber. Auth:client
- `GetBranchReviewsAction` [GET /api/v1/branches/{branch}/reviews] — paginated reviews for a branch. No auth
- `ReviewResource` — id, rating, comment, author_name, created_at
- `SubmitReviewRequest` — validates rating (1-5) + comment (nullable, max 500)
- Routes: reviews.php

### Booking Cancel & Show
- `CancelBookingAction` [POST /api/v1/client/bookings/{booking}/cancel] — validates ownership + confirmed status → canceled. Auth:client
- `ShowBookingAction` [GET /api/v1/bookings/{booking}] — validates ownership, returns booking resource. Auth:client

### Profile Update
- `UpdateProfileAction` [PATCH /api/v1/client/profile] — nullable name/email/password fields. Auth:client
- `UpdateProfileRequest` — validates email (unique ignoring current) + password (min 8, nullable)

### Model Relations
- `BranchModel.reviews()` — MorphMany via subject_type='App\\\\Models\\\\Branch'
- `BranchModel.barbers()` — HasManyThrough via ChairModel
- `BarberModel.reviews()` — MorphMany
- `BarberModel.bookings()` — HasMany (for completed booking validation)
- `ClientModel.bookings()` — HasMany

### Brand API
- `ListBrandsAction` [GET /api/v1/brands] — lists all brands. No auth.
- `ShowBrandAction` [GET /api/v1/brands/{brand}] — single brand detail. No auth.
- Routes: brands.php

### BarberAffiliation API
- `CreateAffiliationAction` [POST /api/v1/barbers/{barber}/affiliate] — invite barber. Auth:barber
- `AcceptAffiliationAction` [POST /api/v1/affiliations/{affiliation}/accept] — accept invite. Auth:barber
- `RejectAffiliationAction` [POST /api/v1/affiliations/{affiliation}/reject] — reject invite. Auth:barber
- `ListBarberAffiliationsAction` [GET /api/v1/barbers/{barber}/affiliations] — list barber's affiliations. Auth:barber
- Routes: barber-affiliations.php

### JobPosting API
- `ListJobPostingsAction` [GET /api/v1/jobs] — list active job postings. No auth.
- `ShowJobPostingAction` [GET /api/v1/jobs/{job}] — single job detail. No auth.
- `ApplyForJobAction` [POST /api/v1/jobs/{job}/apply] — apply as barber. Auth:barber
- Routes: jobs.php

### Frontend Consumption
- Brand list/detail screens (Flutter client app) — connected to Brand API (GET /brands, GET /brands/{id})
- BarberAffiliation list/accept/reject screens (Flutter client app) — connected to BarberAffiliation API
- JobPosting list/detail/apply screens (Flutter client app) — connected to JobPosting API
- All three consume APIs via `ExploreRepositoryImpl` / `BrandRepositoryImpl` / dedicated BLoC or local patterns
- `ApiEndpoints.dart` (dorak_core) updated with all three module endpoints
- Translations added for all three features

### Skills
- `10StrictBackendArchitecture/SKILL.md` — FormRequest enforcement, Command objects, strict imports, Resource composition
