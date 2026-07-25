# Dorak Platform — Execution Status

> **Last Updated:** 2026-07-25

---

## Completed Phases

### Phase 0: Technical Concepts Document ✅
- Created `docs/13_technical-concepts.md` (backend + frontend copies)
- 10 CRUD concepts (optimistic/pessimistic, version locking, idempotency, soft delete, PATCH/PUT, race conditions, batch ops, retry backoff, consistency, field projection)
- 10 Flutter frontend concepts (TextFormField, debounce, error handling, local/server state, auth guards, CORS, env vars, virtualization, multi-device sync, cache invalidation)
- 3 integration patterns (API response format, auth flow, feature module structure)
- Referenced in `dorak-backend/AGENTS.md` and `dorak-frontend/CLAUDE.md`

### Phase 1: Filament Panels ✅
**Phase 1.1 — Admin Panel Gaps:**
- All 5 "missing" pages already existed on disk (Booking Create, Review Create/Edit, Activation Edit, Application Create)
- PRD updated to mark Track A gaps as resolved

**Phase 1.2 — Barber Panel (5 new resources):**
- `OfferedServiceResource` (List, Create, Edit, View)
- `BookingResource` (List, Edit, View)
- `ReviewResource` (List, View — read-only)
- `ApplicationResource` (List, Create, View)
- `BarberAffiliationResource` (List, View — read-only)
- 41 Filament PHP files created
- `ScopePanelToCurrentUser` middleware updated (BarberAffiliation + Application scopes)

**Phase 1.3 — Branch Panel (7 new resources):**
- `ChairResource` (List, Create, Edit, View)
- `OfferedServiceResource` (List, Create, Edit, View)
- `BookingResource` (List, Edit, View)
- `JobPostingResource` (List, Create, Edit, View)
- `ApplicationResource` (List, View — read-only)
- `BarberAffiliationResource` (List, View — read-only, Accept/Reject actions)
- `ReviewResource` (List, View — read-only)
- 53 Filament PHP files created
- `ScopePanelToCurrentUser` middleware updated (Branch-side scopes)

### Phase 2: API Endpoints ✅
**Phase 2.1 — Barber API (8 endpoints):**
- `POST /barber/login` (public — Sanctum token)
- `GET /barber/profile` (auth:barber)
- `PATCH /barber/profile` (auth:barber)
- `POST /barber/portfolio` (auth:barber — multipart upload)
- `DELETE /barber/portfolio/{photo}` (auth:barber)
- `PATCH /barber/travel-radius` (auth:barber)
- `GET /barber/schedule` (auth:barber)
- `PATCH /barber/schedule` (auth:barber)
- 3 migrations (travel_radius, portfolio_photos, schedules)
- 2 models (BarberPortfolioPhotoModel, BarberScheduleModel)
- 14 CQRS commands/handlers, 7 repositories, 5 requests, 31 tests

**Phase 2.2 — Branch API (15 endpoints):**
- `POST /branch/login` (public — `branch_api` Sanctum guard)
- `GET /branch/dashboard` (auth:branch_api)
- `GET /branch/profile` (auth:branch_api)
- `PATCH /branch/profile` (auth:branch_api)
- `PATCH /chairs/{chair}/status` (auth:branch_api — fires ChairStatusUpdated → Reverb)
- `GET /branch/affiliations` (auth:branch_api)
- `POST /branch/affiliations/{id}/accept` (auth:branch_api)
- `POST /branch/affiliations/{id}/reject` (auth:branch_api)
- `GET /branch/bookings` (auth:branch_api)
- `GET /branch/job-postings` (auth:branch_api)
- `POST /branch/job-postings` (auth:branch_api)
- `PUT /branch/job-postings/{id}` (auth:branch_api)
- `DELETE /branch/job-postings/{id}` (auth:branch_api)
- `GET /branch/job-postings/{id}/applications` (auth:branch_api)
- `GET /branch/reviews` (auth:branch_api)
- `branch_api` guard added to auth.php config
- BranchModel got `HasApiTokens` trait
- 19 files created, 27 contract tests

### Phase 3: Flutter Feature Specs — Partial ✅
**Phase 3.1 — Barber App Specs: ✅**
- 6 backend feature specs (BAR-AUTH, BAR-PROFILE, BAR-SERVICES, BAR-AFFILIATIONS, BAR-ATHOME, BAR-JOBS)
- 6 frontend feature specs (FE-AUTH, FE-PROFILE, FE-SERVICES, FE-AFFILIATIONS, FE-ATHOME, FE-JOBS)
- `docs/feature-index.md` with build order
- `CLAUDE.md` with architecture conventions

**Phase 3.2 — Business App Specs: ✅**
- 6/6 backend specs (BIZ-AUTH, BIZ-DASHBOARD, BIZ-CHAIRS, BIZ-AFFILIATIONS, BIZ-BOOKINGS, BIZ-JOBS)
- 6/6 frontend specs (FE-AUTH, FE-DASHBOARD, FE-CHAIRS, FE-AFFILIATIONS, FE-BOOKINGS, FE-JOBS)
- `docs/feature-index.md` with build order
- `CLAUDE.md` with architecture conventions + Reverb real-time

---

## Test Status

| Layer | Tests | Failures |
|-------|-------|----------|
| Backend (Laravel/Pest) | **401** | **0** |
| Frontend Client App | **294** | **0** |
| **Total** | **695** | **0** |

---

## Files Created/Modified Summary

### Backend (`dorak-backend/`)
| Category | Count |
|----------|-------|
| Filament resources (Barber Panel) | 41 files |
| Filament resources (Branch Panel) | 53 files |
| API Actions (Barber) | 8 |
| API Actions (Branch) | 15 |
| Migrations | 3 |
| Models | 2 |
| CQRS (Commands/Handlers) | 14 |
| Repositories | 7 |
| Requests | 5 |
| Routes | 2 files |
| Factories | 2 |
| Tests | 8 files, ~58 tests |
| Middleware updated | 1 (ScopePanelToCurrentUser) |
| Config updated | 1 (auth.php — branch_api guard) |
| Docs | 13_technical-concepts.md |

### Frontend (`dorak-frontend/`)
| Category | Count |
|----------|-------|
| Barber App feature specs | 12 files |
| Business App feature specs | 12 files |
| CLAUDE.md | 2 (barber + business) |
| feature-index.md | 2 (barber + business) |
| Tech concepts doc | 1 copy |
| CLAUDE.md updated | 1 (root) |

---

## Remaining Work

1. **Phase 3.3: Barber App Flutter implementation** (6 features: AUTH → PROFILE → AFFILIATIONS → SERVICES → ATHOME → JOBS)
2. **Phase 3.4: Business App Flutter implementation** (6 features: AUTH → DASHBOARD → CHAIRS → AFFILIATIONS → BOOKINGS → JOBS)

### Dependency Graph
```
Phase 3.3 (Barber) ─────────────────────────┐
  BAR-AUTH → BAR-PROFILE → BAR-AFFILIATIONS │
                   ↓                         │
              BAR-SERVICES                   │
                   ↓                         │
              BAR-ATHOME                     │
                   ↓                         │
              BAR-JOBS ─────────────────────┤
                                             │
Phase 3.4 (Business) ───────────────────────┘
  BIZ-AUTH → BIZ-DASHBOARD → BIZ-CHAIRS (Reverb)
                   ↓
              BIZ-AFFILIATIONS
                   ↓
              BIZ-BOOKINGS
                   ↓
              BIZ-JOBS
```

### Parallelism
- Phase 3.3 and 3.4 can run in parallel (independent apps)
- Within each app, features must follow dependency order
- BAR-AUTH and BIZ-AUTH can run in parallel
