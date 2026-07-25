# Dorak Platform — Post-Launch Improvements

> **Created:** 2026-07-25
> **Status:** In Progress

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

### Step 1: Arabic Translations — Fix 13 Missing Keys
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

**Files to modify:**
- `dorak-frontend/apps/dorak_client_app/assets/translations/en.json`
- `dorak-frontend/apps/dorak_client_app/assets/translations/ar.json`
- `dorak-frontend/apps/dorak_barber_app/assets/translations/en.json`
- `dorak-frontend/apps/dorak_barber_app/assets/translations/ar.json`
- `dorak-frontend/apps/dorak_barber_app/lib/Features/Affiliations/Presentation/AffiliationsScreen.dart`

---

### Step 2: FlutterSecureStorage — Upgrade Token Security
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

**Files to modify per app:**

| App | Files |
|-----|-------|
| **Client** | `pubspec.yaml`, `TokenManager.dart`, `AuthRepositoryImpl.dart`, `router.dart:45`, `injection.dart`, `injection.config.dart`, `test/helpers/test_app.dart` |
| **Barber** | `pubspec.yaml`, `BarberTokenManager.dart`, `injection.dart`, `injection.config.dart` |
| **Business** | `BusinessTokenManager.dart`, `injection.dart`, `injection.config.dart` |

**Key risk:** `router.dart` redirect currently uses sync `authRepo.isAuthenticated`. Must become async — GoRouter supports `Future<String?>` return from redirect, so this works.

---

### Step 3: Image Caching — Add cached_network_image
**Risk:** Low | **Impact:** High (perf + data usage) | **Effort:** 1-2 hours

**Problem:** 6 `Image.network()` calls re-download on every rebuild. No caching anywhere.

**Plan:**
1. Add `cached_network_image: ^4.0.0` + `flutter_cache_manager: ^4.0.0` to `dorak_core/pubspec.yaml`
2. Create `DorakCachedImage` widget in dorak_core (consistent placeholder/error/fit)
3. Replace 6 `Image.network()` calls in Client App

**Files to modify:**
- `dorak_core/pubspec.yaml` — add deps
- `dorak_core/lib/Src/Widgets/DorakCachedImage.dart` — new widget
- `dorak_core/lib/DorakCore.dart` — export
- Client App: `FacePhotoCard.dart:27`, `ServiceCatalogCard.dart:17`, `ServiceCatalogItemDetailScreenBodyState.dart:41`, `HistoryMediaGrid.dart:33`, `BrandCard.dart:17`, `BrandDetailScreenBodyState.dart:39`

---

### Step 4: BlocSelector Optimization
**Risk:** Medium (needs testing) | **Impact:** Medium (CPU) | **Effort:** 2-3 hours

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

### Step 5: Widget Splitting — Files >100 Lines
**Risk:** None | **Impact:** Code quality | **Effort:** 1-2 hours

**Worst offenders to split:**
| File | Lines | Split Strategy |
|------|-------|----------------|
| `business_app/.../JobForm.dart` | 198 | Extract individual field widgets |
| `client_app/.../ServiceCatalogItemDetailScreenBodyState.dart` | 190 | Extract section widgets |
| `client_app/.../FaceProfileScreenBodyState.dart` | 155 | Extract photo grid + analysis |
| `client_app/.../ExploreScreenBodyState.dart` | 151 | Extract tab views |
| `client_app/.../DiscoveryPreferencesScreenBody.dart` | 142 | Extract form sections |

---

### Step 6: Test Coverage — Barber + Business Apps
**Risk:** None | **Impact:** Regression protection | **Effort:** 4-6 hours (incremental)

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
Step 1: Translations (30 min)     ← zero risk, immediate fix
Step 2: SecureStorage (2-3 hrs)   ← security-critical, mechanical
Step 3: Image caching (1-2 hrs)   ← high-impact perf
Step 4: BlocSelector (2-3 hrs)    ← needs testing
Step 5: Widget splitting (1-2 hrs)← code quality only
Step 6: Test coverage (4-6 hrs)   ← incremental
```

**Total estimated effort:** 11-17 hours
