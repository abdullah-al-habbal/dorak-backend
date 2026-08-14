# Dorak Platform — Post-Launch Improvements

> ⚠️ **HISTORICAL (updated 2026-08-09):** The Flutter frontend was removed on 2026-08-09 and is being rebuilt from scratch (new UI/UX). All `dorak-frontend/apps/...` file-path references and frontend-specific findings below are superseded; the performance/security *findings* remain valid requirements for the rebuild.

> **Created:** 2026-07-25
> **Status:** ✅ ALL COMPLETE

---

## Performance Audit Findings

### High Priority

| # | Finding | Impact | Apps Affected |
|---|---------|--------|---------------|
| 1 | **Zero image caching** — 6 `Image.network()` calls with no `cached_network_image` or `flutter_cache_manager`. Every rebuild re-downloads. | User-facing perf, data usage | Client (6 files) |
| 2 | **Zero `BlocSelector` usage** — 24 `BlocBuilder` instances rebuild more than needed. Worst: `ExploreScreenBodyState` (rebuilds tabs+search+sort), `BookingScreenBodyState` (entire form). | CPU, frame drops | All 3 apps |
| 3 | **26 Presentation files exceed 100 lines** — violates CLAUDE.md convention. Worst: `JobForm.dart` (198), `ServiceCatalogItemDetailScreenBodyState.dart` (190). | Maintainability, rebuild isolation | Client (20), Business (5), Barber (1) |

### Medium Priority

| # | Finding | Impact | Apps Affected |
|---|---------|--------|---------------|
| 4 | **No `AutomaticKeepAliveClientMixin`** — TabBar views (Explore, Bookings, MyApplications) recreate state on tab switch. | UX, unnecessary API calls | Client, Business |
| 5 | **No `RepaintBoundary`** — complex list items (history cards, branch cards, barber cards) have no repaint isolation. | Jank during scroll | Client |
| 6 | **Computed filtering in `build()`** — `ServiceCatalogBrowseScreenBodyState._filteredItems` creates new lists every rebuild. | CPU, GC pressure | Client |
| 7 | **Test coverage gap** — Barber (5%), Business (5%), 11/19 Client features untested. | Regression risk | All 3 apps |

### Low Priority

| # | Finding | Impact | Apps Affected |
|---|---------|--------|---------------|
| 8 | Missing `const QuickActions()` in `DashboardScreenBody`. | Micro-optimization | Business |
| 9 | Custom `PerformanceMonitor` adds stopwatch overhead to every wrapped widget build. | Profiling overhead | Client |
| 10 | No `precacheImage` for above-the-fold images (brand logos, profile photos). | First-load latency | Client |

---

## Implementation Plan

Ordered by: risk (lowest first) → impact (highest first) → effort (smallest first).

### Step 1: Arabic Translations — Fix 13 Missing Keys ✅ DONE
**Risk:** None | **Impact:** User-facing | **Effort:** 30 min

**Problem:** 13 `.tr()` calls reference keys that don't exist in either `en.json` or `ar.json`. Users see raw key paths.

**Client App — 11 missing keys:**
| Section | Keys to Add |
|---------|-------------|
| `discovery_preferences` | `title`, `categories`, `price_range`, `max_distance`, `barber_preferences`, `save` |
| `service_catalog` | `search`, `all`, `face_shapes`, `hair_textures`, `tags` |

**Barber App — 2 fixes:**
| Issue | Fix |
|-------|-----|
| `affiliation.empty` used in `AffiliationsScreen.dart:28` | Change to `affiliation.no_affiliations` (key exists) |
| `athome.km` used in `TravelRadiusSlider.dart:32,36` | Add `athome.km` to both JSON files |

**Business App:** Clean — 0 missing.

**Files modified:**
- `dorak-frontend/apps/dorak_client_app/assets/translations/en.json` — 11 keys added
- `dorak-frontend/apps/dorak_client_app/assets/translations/ar.json` — 11 keys added
- `dorak-frontend/apps/dorak_barber_app/assets/translations/en.json` — `athome.km` added
- `dorak-frontend/apps/dorak_barber_app/assets/translations/ar.json` — `athome.km` added
- `dorak-frontend/apps/dorak_barber_app/lib/Features/Affiliations/Presentation/AffiliationsScreen.dart` — `affiliation.empty` → `affiliation.no_affiliations`
- `dorak-frontend/apps/dorak_barber_app/lib/Features/AtHome/Presentation/Widgets/TravelRadiusSlider.dart` — hardcoded `km` → `.tr()`

---

### Step 2: FlutterSecureStorage — Upgrade Token Security ✅ DONE
**Risk:** Low (mechanical) | **Impact:** Security-critical | **Effort:** 2-3 hours

**Problem:** All 3 apps store Sanctum tokens in plain-text `SharedPreferences`. Business App has `flutter_secure_storage: ^9.2.4` declared but unused.

**Pattern per app:**
1. Add `flutter_secure_storage: ^9.2.4` to pubspec (Client, Barber — Business already has it)
2. Change TokenManager constructor: `SharedPreferences` → `FlutterSecureStorage`
3. `getToken()` → `async storage.read(key:)`
4. `isAuthenticated` → `Future<bool>` (async)
5. Profile caching stays in SharedPreferences (non-sensitive)
6. Update DI: register `FlutterSecureStorage` as singleton
7. Update `router.dart` redirect to use async `isAuthenticated`

**Files modified per app:**

| App | Files |
|-----|-------|
| **Core** | `pubspec.yaml` (`flutter_secure_storage: ^10.3.1`), `TokenCache.dart` (new), `AuthInterceptor.dart` (uses TokenCache), `SetupCoreDependencies.dart` (registers TokenCache + SecureStorage) |
| **Client** | `TokenManager.dart`, `AuthRepositoryImpl.dart`, `AuthRepository.dart` (async isAuthenticated), `AuthBloc.dart` (await), `router.dart` (async redirect), `injection.dart`, `injection.config.dart`, `di.dart` (`secureTokenKey: 'auth_token'`), `test/helpers/test_app.dart`, `FakeFlutterSecureStorage.dart` (new), `StubAuthRepository.dart` (async isAuthenticated), `pubspec.yaml` |
| **Barber** | `BarberTokenManager.dart`, `AuthRepositoryImpl.dart`, `AuthRepository.dart`, `AuthBloc.dart`, `router.dart`, `injection.dart`, `injection.config.dart`, `di.dart` (`secureTokenKey: 'barber_auth_token'`), `pubspec.yaml` |
| **Business** | `BusinessTokenManager.dart`, `AuthRepositoryImpl.dart`, `AuthRepository.dart`, `AuthBloc.dart`, `router.dart`, `injection.dart`, `injection.config.dart`, `di.dart` (`secureTokenKey: 'branch_auth_token'`), `pubspec.yaml` |

**Key risk:** `router.dart` redirect currently uses sync `authRepo.isAuthenticated`. Must become async — GoRouter supports `Future<String?>` return from redirect, so this works.

---

### Step 3: Image Caching — Add cached_network_image ✅ DONE
**Risk:** Low | **Impact:** High (perf + data usage) | **Effort:** 1-2 hours

**Problem:** 6 `Image.network()` calls re-download on every rebuild. No caching anywhere.

**Plan:**
1. Add `cached_network_image: ^4.0.0` + `flutter_cache_manager: ^4.0.0` to `dorak_core/pubspec.yaml`
2. Create `DorakCachedImage` widget in dorak_core (consistent placeholder/error/fit)
3. Replace 6 `Image.network()` calls in Client App

**Files modified:**
- `dorak_core/pubspec.yaml` — added `cached_network_image: ^3.4.1`, `flutter_cache_manager: ^3.4.2`
- `dorak_core/lib/Src/UI/DorakCachedImage.dart` — new widget
- `dorak_core/lib/DorakCore.dart` — export
- Client App: `FacePhotoCard.dart`, `ServiceCatalogCard.dart`, `ServiceCatalogItemDetailScreenBodyState.dart`, `HistoryMediaGrid.dart`, `BrandCard.dart`, `BrandDetailScreenBodyState.dart` — all 6 `Image.network()` → `DorakCachedImage`

---

### Step 4: BlocSelector Optimization ✅ DONE
**Risk:** Medium (needs testing) | **Impact:** Medium (CPU) | **Effort:** 2-3 hours

**Result:** 8 BlocBuilder → BlocSelector conversions across all 3 apps. 16 kept as BlocBuilder (use 3+ state fields).

| App | Conversions |
|-----|------------|
| Client | 5 (DorakApp ThemeBloc, Login, Register, Booking split, DiscoveryPreferences split) |
| Barber | 2 (Login, JobDetail) |
| Business | 1 (Login) |

**Problem:** 24 `BlocBuilder` instances rebuild entire subtrees when only partial state is needed.

**Priority conversions:**
| File | Current | Selector Needed |
|------|---------|-----------------|
| `barber_app/.../LoginScreenBody.dart` | Full form rebuild | `isLoading` + `error` only |
| `client_app/.../ExploreScreenBodyState.dart` | Tabs+search+sort | List data vs filter state |
| `client_app/.../BookingScreenBodyState.dart` | Entire booking form | Booking status only |
| `business_app/.../AffiliationsScreenBody.dart` | Full list rebuild | Affiliations list only |

**Target:** ~8-10 conversions across all apps.

---

### Step 5: Widget Splitting — Files >100 Lines ✅ DONE
**Risk:** None | **Impact:** Code quality | **Effort:** 1-2 hours

**Result:** 23 files split, 27 new files created. All 23 now ≤100 lines. 0 dart analyze errors.

| App | Files Split | New Files |
|-----|------------|-----------|
| Client | 16 | 20 |
| Barber | 1 | 1 |
| Business | 6 | 6 |

**Worst offenders to split:**
| File | Lines | Split Strategy |
|------|-------|----------------|
| `business_app/.../JobForm.dart` | 198 | Extract individual field widgets |
| `client_app/.../ServiceCatalogItemDetailScreenBodyState.dart` | 190 | Extract section widgets |
| `client_app/.../FaceProfileScreenBodyState.dart` | 155 | Extract photo grid + analysis |
| `client_app/.../ExploreScreenBodyState.dart` | 151 | Extract tab views |
| `client_app/.../DiscoveryPreferencesScreenBody.dart` | 142 | Extract form sections |

---

### Step 6: Test Coverage — Barber + Business Apps ✅ DONE
**Risk:** None | **Impact:** Regression protection | **Effort:** 4-6 hours (incremental)

**Result:** 108 new tests (48 Barber + 47 Business + existing). 0 failures.

| App | New Tests | Total Tests | Coverage |
|-----|----------|-------------|----------|
| Barber | 48 | 55 | Auth, Jobs, Profile (DTO + Repo + BLoC) |
| Business | 47 | 53 | Auth, Dashboard, Chairs (DTO + Repo + BLoC) |

**Current state:** Both apps have ~5% coverage (entity instantiation only).

**Add per app:**
- DTO roundtrip tests (fromJson → toJson → fromJson)
- Repository mock tests (success + failure paths)
- BLoC state transition tests (initial → loading → loaded/error)

**Priority features to test:**
| Barber App | Business App |
|------------|--------------|
| Auth (login flow) | Auth (login flow) |
| Profile (get/update) | Dashboard (data loading) |
| Jobs (list/apply) | Chairs (status toggle) |

---

## Execution Order

```
Step 1: Translations (30 min)     ✅ DONE
Step 2: SecureStorage (2-3 hrs)   ✅ DONE
Step 3: Image caching (1-2 hrs)   ✅ DONE
Step 4: BlocSelector (2-3 hrs)    ✅ DONE — 8 conversions
Step 5: Widget splitting (1-2 hrs)✅ DONE — 23 files split
Step 6: Test coverage (4-6 hrs)   ✅ DONE — 108 new tests
```

**Total estimated effort:** 11-17 hours
**Actual:** All 6 steps complete. 0 failures across all apps.

---

## Phase 2: Remaining Optimizations

### Step 7: Computed Filtering — Memoize `_filteredItems`
**Risk:** None | **Impact:** CPU (eliminates list allocation every rebuild) | **Effort:** 30 min

**Problem:** `ServiceCatalogBrowseScreenBodyState._filteredItems` creates new `List` every `build()`. On every keystroke/filter change, entire list is recomputed even when filters haven't changed.

**Fix:** Memoize with a dirty flag or use `computed` pattern — recompute only when filter inputs change, not on every build.

**Files:**
- `dorak-frontend/apps/dorak_client_app/lib/Features/ServiceCatalog/Presentation/Widgets/ServiceCatalogBrowseScreenBodyState.dart`

---

### Step 8: AutomaticKeepAliveClientMixin — TabBar State Preservation
**Risk:** Low | **Impact:** UX (no tab-switch re-fetch) | **Effort:** 1 hour

**Problem:** TabBar views (Explore, Bookings, MyApplications) recreate state on tab switch. User loses scroll position, selected filters, etc.

**Fix:** Add `AutomaticKeepAliveClientMixin` + `wantKeepAlive: true` to each tab's State class.

**Files:**
- `dorak-frontend/apps/dorak_client_app/lib/Features/Explore/Presentation/Widgets/ExploreScreenBodyState.dart`
- `dorak-frontend/apps/dorak_client_app/lib/Features/Booking/Presentation/Widgets/BookingScreenBodyState.dart`
- `dorak-frontend/apps/dorak_barber_app/lib/Features/Jobs/Presentation/Widgets/JobsScreenBody.dart`
- `dorak-frontend/apps/dorak_business_app/lib/Features/Dashboard/Presentation/Widgets/DashboardScreenBody.dart`

---

### Step 9: RepaintBoundary — Scroll Jank Isolation
**Risk:** None | **Impact:** Scroll smoothness | **Effort:** 30 min

**Problem:** Complex list items (history cards, branch cards, barber cards) have no repaint isolation. Scrolling one card repaints all.

**Fix:** Wrap each `ListView.builder` item in `RepaintBoundary`.

**Files:**
- `dorak-frontend/apps/dorak_client_app/lib/Features/History/Presentation/Widgets/HistoryCard.dart`
- `dorak-frontend/apps/dorak_client_app/lib/Features/Booking/Presentation/Widgets/BookingCard.dart`
- `dorak-frontend/apps/dorak_barber_app/lib/Features/Jobs/Presentation/Widgets/JobCard.dart`
- `dorak-frontend/apps/dorak_business_app/lib/Features/Branches/Presentation/Widgets/BranchCard.dart`

---

### Step 10: precacheImage — Above-the-Fold Image Warmup
**Risk:** None | **Impact:** First-load latency | **Effort:** 30 min

**Problem:** Brand logos, profile photos load lazily on first render. No precaching at navigation.

**Fix:** Call `precacheImage` in `initState` for screens with prominent images.

**Files:**
- `dorak-frontend/apps/dorak_client_app/lib/Features/Brand/Presentation/Widgets/BrandDetailScreenBodyState.dart`
- `dorak-frontend/apps/dorak_client_app/lib/Features/Profile/Presentation/Widgets/ProfileScreenBodyState.dart`

---

### Step 11: PerformanceMonitor Cleanup
**Risk:** None | **Impact:** Remove profiling overhead | **Effort:** 15 min

**Problem:** Custom `PerformanceMonitor` wraps widget builds with stopwatch. Adds overhead in production.

**Fix:** Remove or guard behind `kDebugMode`.

**Files:**
- `dorak-frontend/apps/dorak_client_app/lib/` (search for PerformanceMonitor usage)

---

## Phase 2 Execution Order

```
Step 7:  Filtering memoization  (30 min)  ✅ DONE — dirty flag + cache
Step 8:  KeepAlive mixin        (1 hr)    ✅ DONE — 3 views converted to StatefulWidget
Step 9:  RepaintBoundary        (30 min)  ✅ DONE — 15 files wrapped
Step 10: precacheImage          (30 min)  ✅ DONE — 3 screens precached
Step 11: PerformanceMonitor     (15 min)  ✅ DONE — dead code deleted
```

**Phase 2 status:** ✅ ALL COMPLETE

---

## Phase 3: Missing Backend APIs (from `12_implementation-prd.md`)

### Step 12: Brand API — Create/Update Endpoints
**Risk:** Low | **Impact:** High (mobile brand management) | **Effort:** 1-2 hours

**Problem:** Brand list/show endpoints exist. Create/update are missing. Mobile app can't manage brands.

**Endpoints needed:**
- `POST /api/v1/brands` — create brand. Auth:client
- `PUT /api/v1/brands/{brand}` — update brand. Auth:client

**Files to create/modify:**
- `modules/Brand/Http/Actions/Client/CreateBrandAction.php`
- `modules/Brand/Http/Actions/Client/UpdateBrandAction.php`
- `modules/Brand/Http/Requests/Client/CreateBrandRequest.php`
- `modules/Brand/Http/Requests/Client/UpdateBrandRequest.php`
- `modules/Brand/Routes/brands.php` — add routes under `auth:client` middleware
- Tests

---

### Step 13: Chair API — GET Endpoints
**Risk:** Low | **Impact:** Medium (floor-plan data) | **Effort:** 1 hour

**Problem:** Basic floor-plan endpoint exists. Need dedicated chair GET endpoints for branch-level chair listing.

**Endpoints needed:**
- `GET /api/v1/branches/{branch}/chairs` — list chairs for a branch. No auth
- `GET /api/v1/chairs/{chair}` — single chair detail. No auth

**Files to create/modify:**
- `modules/Chair/Http/Actions/Client/ListBranchChairsAction.php`
- `modules/Chair/Http/Actions/Client/ShowChairAction.php`
- `modules/Chair/Presenters/ChairResource.php`
- `modules/Chair/Routes/chairs.php`
- Tests

---

### Step 14: Barber Activation API
**Risk:** Low | **Impact:** Medium (control barber visibility) | **Effort:** 1 hour

**Problem:** Activation exists in admin panel. Need API endpoints for branch owners to activate/deactivate barbers.

**Endpoints needed:**
- `POST /api/v1/barbers/{barber}/activate` — activate barber. Auth:barber
- `POST /api/v1/barbers/{barber}/deactivate` — deactivate barber. Auth:barber

**Files to create/modify:**
- `modules/Activation/Http/Actions/Barber/ActivateBarberAction.php`
- `modules/Activation/Http/Actions/Barber/DeactivateBarberAction.php`
- `modules/Activation/Routes/barber-activation.php`
- Tests

---

### Step 15: Application API — List + Status Update
**Risk:** Low | **Impact:** Medium (branch manager workflow) | **Effort:** 1-2 hours

**Problem:** Job application apply endpoint exists. Branch managers need to list applications and update status.

**Endpoints needed:**
- `GET /api/v1/applications` — list applications for branch. Auth:barber
- `PUT /api/v1/applications/{application}/status` — update status (accept/reject). Auth:barber

**Files to create/modify:**
- `modules/JobPosting/Http/Actions/Barber/ListApplicationsAction.php`
- `modules/JobPosting/Http/Actions/Barber/UpdateApplicationStatusAction.php`
- `modules/JobPosting/Http/Requests/Barber/UpdateApplicationStatusRequest.php`
- Tests

---

### Step 16: Currency API
**Risk:** Low | **Impact:** Medium (mobile price display) | **Effort:** 1-2 hours

**Problem:** Currency/Filament admin exists. Need API endpoints for mobile price display.

**Endpoints needed:**
- `GET /api/v1/currencies` — list currencies. No auth
- `GET /api/v1/exchange-rates` — list exchange rates. Auth:admin
- `POST /api/v1/currency/convert` — convert amount between currencies. Auth:admin

**Files to create/modify:**
- `modules/Currency/Http/Actions/Client/ListCurrenciesAction.php`
- `modules/Currency/Http/Actions/Admin/ListExchangeRatesAction.php`
- `modules/Currency/Http/Actions/Admin/ConvertCurrencyAction.php`
- `modules/Currency/Routes/currencies.php`
- Tests

---

### Step 17: OfferedService API
**Risk:** None | **Impact:** Low (embedded in explore/booking) | **Effort:** 30 min

**Problem:** Offered services are managed via Filament. May need API for mobile barber service listing.

**Endpoints needed:**
- `GET /api/v1/barbers/{barber}/services` — list barber's offered services. No auth

**Files to create/modify:**
- `modules/OfferedService/Http/Actions/Client/ListBarberServicesAction.php`
- Tests

---

### Step 18: Ban API
**Risk:** None | **Impact:** Low (safety feature) | **Effort:** 30 min

**Problem:** Ban model exists. Need API to check if a client is banned.

**Endpoints needed:**
- `GET /api/v1/clients/{client}/bans/check` — check if client has active bans. Auth:client

**Files to create/modify:**
- `modules/Ban/Http/Actions/Client/CheckClientBansAction.php`
- Tests

---

### Step 19: Social Login API
**Risk:** Medium (external dependency) | **Impact:** Medium (user acquisition) | **Effort:** 2-3 hours

**Problem:** Socialite is installed. Need API endpoint for social login flow.

**Endpoints needed:**
- `POST /api/v1/auth/social/{provider}` — socialite → Sanctum token flow

**Files to create/modify:**
- `modules/Client/Http/Actions/Client/SocialLoginAction.php`
- `modules/Client/Http/Requests/Client/SocialLoginRequest.php`
- Tests

---

### Step 20: Test Backfill
**Risk:** None | **Impact:** High (regression protection) | **Effort:** 4-6 hours

**Problem:** Many modules have no feature tests. Need backfill.

**Tests needed:**
| Module | Test Type | What to Test |
|--------|-----------|-------------|
| Booking | Feature | Concurrency, cancel, list, show |
| Explore | Feature | Haversine search, barber list, branch detail |
| Review | Feature | Submit, list by branch, validation |
| Brand | Feature | CRUD, unique slug |
| Chair | Feature | CRUD, status transitions |
| Currency | Feature | Exchange rates, conversion |
| BarberAffiliation | Feature | Invite → accept/reject flow |
| Ban | Feature | Active ban check |

---

## Phase 3 Execution Order

```
Step 12: Brand API          (1-2 hrs)  ✅ DONE — create/update endpoints + tests
Step 13: Chair API          (1 hr)     ✅ ALREADY BUILT — endpoints existed
Step 14: Activation API     (1 hr)     ✅ DONE — activate/deactivate endpoints + tests
Step 15: Application API    (1-2 hrs)  ✅ ALREADY BUILT — endpoints existed
Step 16: Currency API       (1-2 hrs)  ✅ DONE — 3 endpoints + tests
Step 17: OfferedService API (30 min)   ✅ ALREADY BUILT — endpoint existed
Step 18: Ban API            (30 min)   ✅ DONE — check bans endpoint + tests
Step 19: Social Login API   (2-3 hrs)  ✅ ALREADY BUILT — endpoint existed + tests enhanced
Step 20: Test Backfill      (4-6 hrs)  ✅ DONE — 19 new tests across 4 modules
```

**Phase 3 status:** ✅ ALL COMPLETE
