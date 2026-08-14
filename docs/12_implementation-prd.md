# 12 — Implementation PRD: From Current State to MVP

> ⚠️ **HISTORICAL (updated 2026-08-09):** All Track A + Track B gaps resolved. The Flutter frontend was removed on 2026-08-09 and is being rebuilt from scratch (new UI/UX). Sections referencing `dorak_client_app` screens (§1 Track B consumer, §4) are superseded — treat API endpoints as the stable contract.

> **Preface:** All 20 backend modules have database schema + Filament admin panels. "CRUD" in Dorak means **Filament management UI**, not REST API. This document clarifies what exists, what's missing, and what to build in what order — across **two tracks**: Admin Panel (Filament) and Client API (REST).
> Consumes the current-state snapshot from `dorak/report.md` and the ticket definitions in `process/KANBAN.md`.

---

## 1. The Two Tracks

| Track | What it builds | Consumer | Priority now |
|-------|---------------|----------|-------------|
| **A — Admin Panel** | Filament resources, pages, actions, widgets | Brand owners, branch managers, platform admins | **Finish gaps first** (blocker for Track B) |
| **B — Client API** | REST endpoints consumed by mobile apps | `dorak_client_app` (Flutter), future barber/business apps | **After Track A** for each module |

Each module may need Track A, Track B, or both:

| Module | A: Filament | B: API | Status |
|--------|-------------|--------|--------|
| Core | Foundation config | Health check | ✅ Both |
| Client | Admin CRUD | Auth + Profile | ✅ Both |
| Explore | — | Branches, Barbers, details | B ✅ only |
| Booking | Admin CRUD | Create, Cancel, Show, List | A ✅; B ✅ |
| Review | Admin CRUD | Submit, List by branch | A ✅; B ✅ |
| Floor Plan | — | Get floor plan | B ✅ only |
| Marketing | — | CMS pages | B ✅ only |
| Website | — | Web pages | B ✅ only |
| Branch | Admin + Branch panels | (Explore covers read) | A ✅; B partial |
| Barber | Admin + Barber panels | (Explore covers read) | A ✅; B partial |
| Brand | Standard CRUD | **Needed** | A ✅; B ✅ (list, show) |
| Chair | Standard CRUD | **Needed** | A ✅; B missing |
| OfferedService | Standard CRUD | **Needed** | A ✅; B missing |
| Currency + ExchangeRate | Standard CRUD (2 resources) | **Needed** | A ✅; B missing |
| JobPosting + Application | Standard CRUD | **Needed** | A ✅; B ✅ (list, show, apply) |
| BarberAffiliation | Standard CRUD | **Needed** | A ✅; B ✅ (create, accept, reject, list) |
| Activation | Read-only + ToggleAction | **Needed** | A ✅; B missing |
| Ban | Standard CRUD | **Needed** | A ✅; B missing |
| Admin | Standard CRUD | — | A ✅ only |
| Language | Standard CRUD | — | A ✅ only |
| Preference | Standard CRUD | — | A ✅ only |

---

## 2. Track A: Admin Panel Gaps

All Filament resources exist. The following **pages** were missing:

| Module | Missing Page | Status |
|--------|-------------|--------|
| Booking | `CreateBookingPage` | ✅ Created |
| Review | `CreateReviewPage`, `EditReviewPage` | ✅ Created |
| Activation | `EditActivationLogPage` | ✅ Created |
| JobPosting.Application | `CreateApplicationPage` | ✅ Created |

**Total Track A gap:** 5 pages — all resolved ✅

Acceptance criteria:
- [x] Each missing page follows the existing 8-file standard CRUD template
- [x] Pages integrate with existing module policies/gates
- [x] `dart analyze` clean (frontend unaffected — admin-only)

---

## 3. Track B: Client API Gaps

REST API endpoints are missing for modules that the mobile app needs to consume.

### 3.1 High Priority (required for Phase 1 completion)

| Module | Endpoints Needed | Why | KANBAN ref | Status |
|--------|-----------------|-----|------------|--------|
| **Brand** | `GET /brands`, `GET /brands/{id}`, `POST /brands`, `PUT /brands/{id}` | Mobile brand management (list, detail, create, update) | TN-01 | List/show ✅ (GET); create/update missing |
| **Chair** | `GET /branches/{branchId}/chairs`, `GET /chairs/{id}` | Floor-plan chair data beyond the basic floor-plan endpoint | CH-01 | ❌ |
| **BarberAffiliation** | `POST /barbers/{id}/affiliate`, `POST /affiliations/{id}/accept`, `POST /affiliations/{id}/reject`, `GET /barbers/{id}/affiliations` | Invite/accept/reject flow | AF-01 | ✅ All 4 built |
| **Barber Activation** | `POST /barbers/{id}/activate`, `POST /barbers/{id}/deactivate` | Control which universe a barber appears in | (implied by Activation module) | ❌ |

### 3.2 Medium Priority (Phase 2 features)

| Module | Endpoints Needed | Why | KANBAN ref | Status |
|--------|-----------------|-----|------------|--------|
| **JobPosting** | `GET /jobs`, `GET /jobs/{id}`, `POST /jobs/{id}/apply` | B2B job board — list, detail, apply | (implied by Phase 2) | ✅ All 3 built |
| **Application** | `GET /applications`, `PUT /applications/{id}/status` | Branch manager reviews applications | (implied) |
| **Currency** | `GET /currencies`, `GET /exchange-rates`, `GET /convert?from=X&to=Y&amount=Z` | Mobile price display | CU-01 |

### 3.3 Lower Priority (Phase 3)

| Module | Endpoints Needed | Why |
|--------|-----------------|-----|
| **OfferedService** | Admin manages via Filament; may need `GET /barbers/{id}/services` for mobile | Currently embedded in explore/booking flows |
| **Ban** | Read-only check `GET /clients/{id}/bans/check` | Prevent banned clients from booking |
| **Social Login** | `POST /auth/social/{provider}` | Socialite → Sanctum token flow |

### API Pattern (per existing convention)

Each endpoint follows the existing **Action → Handler → EloquentResolver** pattern:

```
modules/{Name}/
  Http/
    Actions/{Name}{Action}Action.php       # Invokable, thin
    Requests/{Name}{Action}Request.php      # Validation
  Handlers/{Name}{Action}Handler.php        # Business logic
  Repositories/{Name}{Action}EloquentResolver.php  # Eloquent only
  Presenters/{Name}Resource.php             # API response shape
```

Acceptance criteria:
- [ ] Each endpoint has passing feature test (happy path + validation error + auth failure)
- [ ] `phpstan analyse` clean at level max
- [ ] Response follows existing API conventions (JSON:API-style, camelCase fields)
- [ ] No `env()` call in API files — config only
- [ ] `declare(strict_types=1)` on every file

---

## 4. Frontend — Client App Gaps

The Flutter app (`dorak_client_app`) needs screens for the new API endpoints.

| Module | Screen Needed | Depends on API |
|--------|--------------|---------------|
| **Brand** | BrandListScreen, BrandDetailScreen, BrandFormScreen | Brand API (B.3.1) |
| **Chair** | ChairListScreen (embedded in floor plan) | Chair API (B.3.1) |
| **BarberAffiliation** | AffiliationInviteScreen, AffiliationStatusScreen | BarberAffiliation API (B.3.1) |
| **JobPosting** | JobListScreen, JobDetailScreen, ApplicationFormScreen | JobPosting API (B.3.2) |

These follow the existing Clean Architecture + `BaseScreenBodyState` pattern.

Acceptance criteria:
- [ ] Each screen uses `BaseScreenBodyState` for loading/error state
- [ ] Each screen `extends BaseScreenBodyState` and uses `executeApiCall`
- [ ] Widgets extracted into typed subfolders (Cards/, Lists/, Buttons/, etc.)
- [ ] `dart analyze` clean

---

## 5. Backfill: Test Coverage

Existing test files (13) cover only Client, Barber, Admin models + health check. The following tests are missing:

| Module | Test Type | What to Test | KANBAN ref |
|--------|-----------|-------------|------------|
| **Booking** | Feature + Unit | Concurrency (two simultaneous bookings on same chair+slot), cancel, list, show | BK-01 |
| **Explore** | Feature | Haversine radius search, barber list, branch detail | (implied) |
| **Review** | Feature | Submit review, list by branch, validation errors | (implied) |
| **Brand** | Feature | CRUD via Filament, unique slug, auto-create branch | TN-01 |
| **Chair** | Feature | CRUD, status transitions, floor-plan position | CH-01 |
| **BarberAffiliation** | Feature | Invite → accept/reject flow, multi-shop constraint | AF-01 |
| **Currency** | Feature | Exchange rate CRUD, conversion calculation | CU-01 |
| All modules | Static | `phpstan analyse` at max level | — |

Acceptance criteria:
- [ ] Each test file passes individually
- [ ] `phpunit` suite green (no regressions)
- [ ] Every house rule (04) has at least one corresponding test

---

## 6. Execution Order

```
Week 1: Track A gaps (5 missing Filament pages) ✅ DONE
  └→ Verified: all 62 tests pass, pages follow 8-file template

Week 2: Track B High — Brand + Chair APIs
  └→ Verify: endpoint tests pass + flutter analyze clean (if frontend wired)

Week 3: Track B High — BarberAffiliation + Activation APIs
  └→ Verify: full API test suite green

Week 4: Backfill tests — Booking concurrency, Explore search, Review submission
  └→ Verify: phpunit green + every 04 house rule tested

Week 5: Track B Medium — JobPosting + Application APIs + Currency API
  └→ Verify: phpstan + phpunit green

Week 6: Frontend screens for new APIs + Filament page gaps
  └→ Verify: dart analyze clean, full-stack smoke test
```

---

## 7. Risks

| Risk | Mitigation |
|------|-----------|
| ~~Filament page gaps block admin workflows~~ | ~~Track A first — 5 pages, 5h, unblocks everything~~ RESOLVED |
| New API endpoints break mobile contract | Follow existing response shapes (Explore/Booking patterns); write contract tests |
| Backfill tests reveal pre-existing bugs | Fix bugs before adding new endpoints; KANBAN Blocked column for anything found |
| Frontend ↔ Backend desync (API changes mid-build) | Freeze API contract per module before mobile starts; communicate via updated route files |
