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

### Strict Enums (Phase 4-7 of master plan)
- 9 Backed Enum files across 5 modules: ChairStatus, BookingStatus, ApplicationStatus, AffiliationStatus, AffiliableType, ChairShape, Locale, Universe, ActivationStatusEnum
- Model casts on ChairModel.status, BookingModel.status, ApplicationModel.status, BarberAffiliationModel.status+affiliable_type, ClientModel.preferred_universe
- Validation `in:` → `Rule::enum` in UpdateChairRequest, GetMarketingPageRequest, UpdateApplicationStatusRequest, CreateAffiliationRequest
- 6 actions fixed: strict enum comparisons instead of string
- Factories/seeders use enum values
- 12StrictEnums/SKILL.md — pattern guide

### Brand CRUD API
- `CreateBrandAction` [POST /api/v1/brands] — create brand. Auth:client
- `UpdateBrandAction` [PUT /api/v1/brands/{brand}] — update brand. Auth:client
- Routes in brands.php under `auth:client` middleware group

### Activation API (Admin)
- `ActivateAction` [POST /api/v1/admin/{entity}/{id}/activate] — activate barber/branch. Auth:admin
- `DeactivateAction` [POST /api/v1/admin/{entity}/{id}/deactivate] — deactivate barber/branch. Auth:admin
- `ToggleActivationAction` — Filament action for admin panel toggle
- Filament pages: EditActivationLogPage, ListActivationLogsPage, ViewActivationLogPage

### Chair API
- `ListChairsAction` [GET /api/v1/chairs] — list chairs. Auth:barber
- `ShowChairAction` [GET /api/v1/chairs/{chair}] — single chair detail. Auth:barber
- `UpdateChairAction` [PUT /api/v1/chairs/{chair}] — update chair. Auth:barber
- Filament pages: Create/Edit/List/View Chair

### Currency API
- `ListCurrenciesAction` [GET /api/v1/currencies] — list currencies. No auth.
- `ListExchangeRatesAction` [GET /api/v1/exchange-rates] — list exchange rates. Auth:admin
- `ConvertCurrencyAction` [POST /api/v1/currency/convert] — convert amount. Auth:admin
- Filament pages: Create/Edit/List/View Currency + ExchangeRate

### Test Backfill (PRD 13 Phase 1)
- Booking concurrency: 3 tests — different chairs same slot, same slot different chairs, race condition via direct insert
- BarberAffiliation multi-shop constraint: CreateAffiliationAction + test for conflict
- Brand Filament list page: load test
- `ErrorCodeEnum::CONFLICT` (HTTP 409) added for conflict responses

### Admin Filament Pages (PRD 13 Phase 2)
- Booking CreatePage — already existed
- Review CreatePage + EditPage — already existed
- Application CreatePage (JobPosting) — already existed
- All admin Filament pages verified present in all modules

### Phase 5 — API Contract Tests (PRD 13)
- `tests/Feature/Contract/ApiResponseContractTest.php` — 40 tests covering all 30+ endpoints
- Verifies: API envelope structure, HTTP status codes, JSON field presence, data types (string/int/array/bool)
- Covers previously untested Client module (6 endpoints: login, register, logout, refresh-token, profile, universe preference)
- Covers previously untested GetBarberDetailAction
- Contract tests pass: 236 total, 1212 assertions

### Bugs Fixed During Contract Tests
- ClientModel: added missing `HasApiTokens` trait (Sanctum) — login/register/refresh-token were broken at runtime
- Universe enum: added missing `Neutral` case — migration default `'neutral'` caused enum cast failure on every client read
- Created Sanctum `personal_access_tokens` migration in Core module — table was missing from test migrations

### Skills
- `10StrictBackendArchitecture/SKILL.md` — FormRequest enforcement, Command objects, strict imports, Resource composition
- `12StrictEnums/SKILL.md` — Backed Enum patterns, model casts, Rule::enum, comparison rules, AND Flutter `@JsonSerializable`/`@JsonKey`/`@JsonEnum` mandate

### JobPostingDto Full Alignment
- Migration `2026_07_16_000001_add_fields_to_job_postings_table` — adds `requirements` (json), `location` (string), `type` (string)
- `JobPostingModel` — updated fillable + `array` cast for `requirements`
- `JobPostingResource` — exposes `requirements`, `location`, `type` in API response
- `JobPostingFactory` — seeds new fields
- `ApiResponseContractTest` — asserts `location` + `type` presence
- Frontend: `JobPostingEntity` `title`/`description` → `Map<String, dynamic>` with locale getters, `status` replaces `isActive`, `requirementsList`
- Frontend: `JobPostingDto` parses new shape, 3 widgets use locale extraction

---

## Remaining Backend Gaps (from `12_implementation-prd.md`)

### Medium Priority
- **Application API** — `GET /applications` (list + filter by status), `PUT /applications/{id}/status` (accept/reject)
  - Needs: `ListApplicationsAction`, `UpdateApplicationStatusAction`, `ApplicationResource`, `UpdateApplicationStatusRequest`
  - Frontend needs: ApplicationListScreen, status update UI

### Lower Priority
- **OfferedService API** — `GET /barbers/{id}/services` (list barber's offered services with prices)
  - Needs: `ListBarberServicesAction`, `ServiceResource`
- **Ban API** — `GET /clients/{id}/bans/check` (return active ban status)
  - Needs: `CheckBanAction`, `BanResource`
- **Social Login** — `POST /auth/social/{provider}` (Socialite → Sanctum token)
  - Needs: `SocialLoginAction`, Socialite provider routing

### Test Gaps
- Frontend widget tests: only 8 exist (BrandFormScreenBody, AffiliationInviteScreenBody, ChairListScreenBody)
- Frontend contract tests: no equivalent of `ApiResponseContractTest` for Flutter DTO parsing
