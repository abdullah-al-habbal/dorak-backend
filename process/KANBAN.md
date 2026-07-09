# KANBAN — the bridge from spec to execution

> Produced in **CRESCENT phase 5**, consumed in **phase 6 (the loop)**, closed in **phase 7 (QA)**. The board *is* the project's execution state — there is no hidden memory. See `process/PROCESS.md` §6.

---

## The board

| Column | Meaning | Who/what moves cards out |
|---|---|---|
| **Backlog** | Decomposed but not yet prioritized | Human prioritizes → Ready |
| **Ready** | Fully specified, all doc IDs + acceptance present, no 🟡 blockers | The loop picks the top card → In Progress |
| **In Progress** | The Ralph loop is actively building it | Passes stopping conditions → In Review/QA |
| **In Review / QA** | Built; being verified against the spec | QA passes → Done; QA fails → back to In Progress |
| **Blocked** | Waiting on a 🟡 *Open* decision (`02_prd.md` §8) or an external answer | Human resolves → Backlog/Ready |
| **Done** | Spec satisfied, suite green, no regressions | — |

**WIP limit:** keep **In Progress** small (suggested 1–2) so the loop finishes tickets instead of fanning out. A ticket should be small enough that **one loop pass can plausibly complete it**; if it can't, split it.

**The Blocked rule:** the loop is allowed to be autonomous **only** against ✅ *Decided* spec. The moment a ticket needs a 🟡 *Open* decision, it goes to **Blocked** and a human is asked — never guessed.

---

## Ticket template

Every ticket carries its **doc IDs**, because acceptance criteria are *derived from the spec*, not invented. Copy this block per ticket.

```
### TICKET <id> — <short title>

- Type:           feature | bug | refactor
- Universe/area:  <e.g. booking, discovery, barber profile, admin panel>

- Implements (doc IDs):
  - Entity:       06 -> <entity name(s)>
  - House rules:  04 -> <rule IDs, e.g. E1, E2, B3>
  - Flow:         07 -> <flow name/number>
  - Edge cases:   08 -> <EC IDs to cover>
  - C4 touchpoint: 09/10 -> <container, if relevant>

- Applicable skills: <e.g. migrations, validations, testing, type-safety, archive-file>

- Acceptance criteria (derived from the IDs above):
  - [ ] <criterion tied to a house rule>
  - [ ] <criterion tied to an edge case>
  - [ ] <criterion tied to the flow's happy path>

- Definition of Done:
  - [ ] a passing test exists for EACH cited house rule and edge case
  - [ ] static analysis clean at target level
  - [ ] full suite green (no regression elsewhere)
  - [ ] files archived before any destructive change

- Blockers (🟡 Open?):  <none | which 02 §8 item>
- Notes:
```

**How acceptance is written:** translate each cited rule/edge-case into a checkable statement. Rule `E1` ("two clients tapping the same chair → exactly one wins") becomes the acceptance line *"concurrent bookings on the same chair+slot resolve to exactly one success; the other gets a clear 'seat taken' error."* The QA phase just checks the boxes.

---

## Worked example

A real ticket for the product's single most important behavior — the no-double-booking guarantee.

```
### TICKET BK-01 — Book a chair with no-double-booking guarantee

- Type:           feature
- Universe/area:  booking

- Implements (doc IDs):
  - Entity:       06 -> Booking, Chair
  - House rules:  04 -> E1 (exactly one wins), E2 (chair+slot locked), B3 (no booking on maintenance)
  - Flow:         07 -> Flow 1 (discover & book a chair)
  - Edge cases:   08 -> EC-1 (simultaneous taps), EC-2 (chair -> maintenance with existing bookings)
  - C4 touchpoint: 10 -> Application & API (booking guarantee), DB

- Applicable skills: migrations, validations, testing, type-safety, concurrency-safety, archive-file

- Acceptance criteria:
  - [ ] booking a free chair+slot locks it to the client (E2)
  - [ ] two concurrent bookings on the same chair+slot -> exactly one succeeds; the other gets a clear "this seat was just taken" error (E1 / EC-1)
  - [ ] a chair in maintenance cannot be booked (B3)
  - [ ] booking statuses use a backed enum, not strings (type-safety; 06)

- Definition of Done:
  - [ ] tests: one per cited rule (E1, E2, B3) and edge case (EC-1, EC-2)
  - [ ] a parallel/concurrent test proves the single-winner guarantee
  - [ ] static analysis clean
  - [ ] full suite green
  - [ ] any touched file archived before destructive change

- Blockers (🟡 Open?):  EC-2 reaccommodation UX is partly 🟡 -> implement the "block booking on maintenance" half; route the "notify + rebook existing" half to a follow-up ticket pending the human decision (02 §8)
- Notes: this is the flagship guarantee; do not mark Done without the concurrency test.
```

Notice the ticket **splits at the 🟡 boundary**: it builds the ✅ *Decided* part (can't book a maintenance chair, single-winner concurrency) and **defers** the 🟡 *Open* part (how to reaccommodate existing bookings when a chair goes to maintenance) to a Blocked follow-up. That split is exactly what keeps the loop autonomous yet safe.

---

## A small starter backlog (illustrative)

Seeded from the MVP scope in `02_prd.md` §4 — re-prioritize as you like.

| id | title | key rules | flow |
|---|---|---|---|
| TN-01 | Create Brand → auto-create first Branch (Branch-First) | A1, A2 | 07 Flow 4 |
| TN-02 | Add second Branch gated by `multi_branch` flag | A3, I2 | 07 Flow 5 |
| BR-01 | Branch storefront with bilingual fields + gender category | A5, J1, J2 | 07 Flow 4 |
| CH-01 | Define chairs with floor-plan position + status | B1, B2, B3 | 07 Flow 1 |
| FP-01 | Backend-driven floor-plan payload (typed resource) | B2, B4, B5 | 07 Flow 1 |
| BK-01 | Book a chair with no-double-booking guarantee | E1, E2, B3 | 07 Flow 1 |
| BB-01 | Standalone barber profile + own services | C1, C2, D1 | 07 Flow 6 |
| AF-01 | Barber affiliation (polymorphic) invite/accept/reject | C3, C4, G4, G5 | 07 Flow 7 |
| CU-01 | Currency on-the-fly conversion + optional dual display | D3, D4, D5 | 07 Flow 10 |

Each becomes a full ticket (template above) when it moves from Backlog → Ready.

---

## Filament panels + resources (CRESCENT run: 2026-06-28)

### Column: Backlog

---

```
### TICKET INFRA-01 — 3 PanelProviders + scoping middleware + config

- Type:           feature
- Universe/area:  admin panel, infrastructure

- Implements (doc IDs):
  - Entity:       06 -> Barber, Branch, Client, Admin (auth entities)
  - House rules:  04 -> H7 (tenancy isolation)
  - Flow:         (auth flows — login, panel access)
  - Edge cases:   none

- Applicable skills: module-architecture, coding-standards

- Acceptance criteria:
  - [ ] AdminPanelProvider serves at `/admin` with guard `admin`
  - [ ] BarberPanelProvider serves at `/barber` with guard `barber_dashboard`
  - [ ] BranchPanelProvider serves at `/branch` with guard `branch`
  - [ ] Each provider dynamically discovers Resources from `modules/*/Filament/Panels/{panel}/`
  - [ ] ScopePanelToCurrentUser middleware scopes barber panel to auth barber's data
  - [ ] ScopePanelToCurrentUser middleware scopes branch panel to auth branch's data
  - [ ] providers.php updated with 3 panel providers after ApplicationServiceProvider
  - [ ] filament config file in Core/Config/filament.php

- Definition of Done:
  - [ ] all 3 panels load at their paths without 500 errors
  - [ ] full suite green (no regressions)

- Blockers:  none
- Notes: dynamic discovery loops `scandir(base_path('modules'))` and registers each module's Filament resources if the directory exists. Scoping middleware uses `filament()->getCurrentPanel()->getId()` to switch rules.
```

---

```
### TICKET SKILL-01 — Filament module structure skill file

- Type:           feature
- Universe/area:  AI agent harness

- Implements (doc IDs):
  - Entity:       none (skill file)
  - House rules:  none
  - Flow:         none
  - Edge cases:   none

- Applicable skills: none (creating a skill)

- Acceptance criteria:
  - [ ] `.claude/skills/filament-module-structure/SKILL.md` exists
  - [ ] Codifies directory tree per module: `Filament/Panels/{Admin,Barber,Branch}/Resources/{Name}/`
  - [ ] States naming rules (Resource suffix, Page suffix, Schema/Table extraction)
  - [ ] States scoping rules via global middleware
  - [ ] States ToggleActivation action pattern

- Definition of Done:
  - [ ] file exists and is internally consistent

- Blockers:  none
- Notes: skill consumed by the Ralph loop in phase 6 when building each resource ticket.
```

---

```
### TICKET ACTN-01 — ToggleActivation reusable action class

- Type:           feature
- Universe/area:  activation module, admin panel

- Implements (doc IDs):
  - Entity:       06 -> ActivationLog, Barber, Branch
  - House rules:  04 -> (activation lifecycle)
  - Flow:         none
  - Edge cases:   none

- Applicable skills: coding-standards

- Acceptance criteria:
  - [ ] `modules/Activation/Filament/Actions/ToggleActivationAction.php` exists
  - [ ] Action creates ActivationLogModel with correct status
  - [ ] Action respects the morph map (`barber`, `branch`)
  - [ ] Can be called from any resource with `ToggleActivationAction::make()`
  - [ ] Runs the observer to sync entity status

- Definition of Done:
  - [ ] action class exists and is unit-testable
  - [ ] full suite green

- Blockers:  none
- Notes: reusable Filament Action class — callable from BarberResource, BranchResource.
```

---

### Column: Ready

(empty)

---

### Column: In Progress

(empty)

---

### Column: In Review / QA

(empty)

---

### Column: Blocked

(empty)

---

### Column: Done

(empty — INFRA-01, SKILL-01, ACTN-01 must be done first)

---

```
### TICKET AUTH-01 — AdminUser resource

- Type:           feature
- Universe/area:  admin panel, auth

- Implements (doc IDs):
  - Entity:       06 -> Admin
  - House rules:  04 -> (admin can manage everything)
  - Flow:         none
  - Edge cases:   none

- Applicable skills: coding-standards, filament-module-structure

- Acceptance criteria:
  - [ ] `modules/Admin/Filament/Panels/Admin/Resources/AdminUserResource/` exists
  - [ ] Resource lists admin users with name, email, created_at
  - [ ] Can create, edit, view, and delete admin users
  - [ ] Resources are auto-discovered by AdminPanelProvider

- Definition of Done:
  - [ ] admin can log in at /admin and see AdminUserResource
  - [ ] full suite green

- Blockers:  none (depends on INFRA-01)
- Notes: first admin resource — validates the discovery pipeline works end-to-end.
```

---

```
### TICKET AUTH-02 — Barber resources (Admin + Barber panel)

- Type:           feature
- Universe/area:  admin panel, barber panel

- Implements (doc IDs):
  - Entity:       06 -> Barber
  - House rules:  04 -> H7 (tenancy isolation — barber sees own data)
  - Flow:         none
  - Edge cases:   none

- Applicable skills: coding-standards, filament-module-structure

- Acceptance criteria:
  - [ ] `modules/Barber/Filament/Panels/Admin/Resources/BarberResource/` exists with full CRUD
  - [ ] BarberResource has ToggleActivation action in the action bar
  - [ ] `modules/Barber/Filament/Panels/Barber/Resources/ProfileResource/` exists (read-only)
  - [ ] ScopePanelToCurrentUser filters ProfileResource to auth barber only
  - [ ] Barber can log in at /barber and see only own profile

- Definition of Done:
  - [ ] admin lists/creates/edits barbers
  - [ ] barber logs into /barber, sees own profile
  - [ ] toggle activation creates ActivationLog record
  - [ ] full suite green

- Blockers:  none (depends on INFRA-01, ACTN-01)
- Notes: first barber panel resource — validates scoping middleware.
```

---

```
### TICKET AUTH-03 — Branch resources (Admin + Branch panel)

- Type:           feature
- Universe/area:  admin panel, branch panel

- Implements (doc IDs):
  - Entity:       06 -> Branch
  - House rules:  04 -> H7 (tenancy isolation — branch sees own data)
  - Flow:         none
  - Edge cases:   none

- Applicable skills: coding-standards, filament-module-structure

- Acceptance criteria:
  - [ ] `modules/Branch/Filament/Panels/Admin/Resources/BranchResource/` exists with full CRUD
  - [ ] BranchResource has ToggleActivation action
  - [ ] `modules/Branch/Filament/Panels/Branch/Resources/ProfileResource/` exists (read-only)
  - [ ] ScopePanelToCurrentUser filters ProfileResource to auth branch only
  - [ ] Branch can log in at /branch and see only own profile

- Definition of Done:
  - [ ] admin lists/creates/edits branches
  - [ ] branch logs into /branch, sees own profile
  - [ ] toggle activation creates ActivationLog record
  - [ ] full suite green

- Blockers:  none (depends on INFRA-01, ACTN-01)
- Notes: first branch panel resource.
```

---

```
### TICKET AUTH-04 — Client resource (Admin panel)

- Type:           feature
- Universe/area:  admin panel

- Implements (doc IDs):
  - Entity:       06 -> Client
  - House rules:  none
  - Flow:         none
  - Edge cases:   none

- Applicable skills: coding-standards, filament-module-structure

- Acceptance criteria:
  - [ ] `modules/Client/Filament/Panels/Admin/Resources/ClientResource/` exists
  - [ ] Shows isBanned status indicator
  - [ ] Full CRUD for clients

- Definition of Done:
  - [ ] admin can manage clients
  - [ ] full suite green

- Blockers:  none (depends on INFRA-01)
- Notes: clients have no panel — they use the mobile app, not Filament.
```

---

```
### TICKET FOUND-01 — Language + Currency + ExchangeRate resources (Admin panel)

- Type:           feature
- Universe/area:  admin panel

- Implements (doc IDs):
  - Entity:       06 -> Language, Currency, ExchangeRate
  - House rules:  none
  - Flow:         none
  - Edge cases:   none

- Applicable skills: coding-standards, filament-module-structure

- Acceptance criteria:
  - [ ] `modules/Language/Filament/Panels/Admin/Resources/LanguageResource/` exists
  - [ ] `modules/Currency/Filament/Panels/Admin/Resources/CurrencyResource/` exists
  - [ ] `modules/Currency/Filament/Panels/Admin/Resources/ExchangeRateResource/` exists
  - [ ] All show name, code, direction/symbol, default flag

- Definition of Done:
  - [ ] admin can CRUD languages, currencies, exchange rates
  - [ ] full suite green

- Blockers:  none (depends on INFRA-01)
- Notes: reference data — simplest resources to build.
```

---

```
### TICKET FOUND-02 — ActivationLog resource (Admin panel)

- Type:           feature
- Universe/area:  admin panel

- Implements (doc IDs):
  - Entity:       06 -> ActivationLog
  - House rules:  none
  - Flow:         none
  - Edge cases:   none

- Applicable skills: coding-standards, filament-module-structure

- Acceptance criteria:
  - [ ] `modules/Activation/Filament/Panels/Admin/Resources/ActivationLogResource/` exists
  - [ ] Read-only list (logs created by ToggleActivation action)
  - [ ] Shows activable type/id, status, admin, reason, timestamps

- Definition of Done:
  - [ ] admin can view activation history
  - [ ] full suite green

- Blockers:  none (depends on INFRA-01)
- Notes: read-only resource; writes happen through ToggleActivationAction.
```

---

```
### TICKET FOUND-03 — Ban resource (Admin panel)

- Type:           feature
- Universe/area:  admin panel

- Implements (doc IDs):
  - Entity:       06 -> Ban
  - House rules:  none
  - Flow:         none
  - Edge cases:   none

- Applicable skills: coding-standards, filament-module-structure

- Acceptance criteria:
  - [ ] `modules/Ban/Filament/Panels/Admin/Resources/BanResource/` exists
  - [ ] Full CRUD for bans
  - [ ] Shows active/permanent status indicator
  - [ ] Can filter by bannable type (client/barber)

- Definition of Done:
  - [ ] admin can manage bans
  - [ ] full suite green

- Blockers:  none (depends on INFRA-01)
- Notes: date-window bans; permanent = banned_until null.
```

---

```
### TICKET CORE-01 — Brand + Preference resources (Admin panel)

- Type:           feature
- Universe/area:  admin panel

- Implements (doc IDs):
  - Entity:       06 -> Brand, Preference
  - House rules:  none
  - Flow:         none
  - Edge cases:   none

- Applicable skills: coding-standards, filament-module-structure

- Acceptance criteria:
  - [ ] `modules/Brand/Filament/Panels/Admin/Resources/BrandResource/` exists
  - [ ] `modules/Preference/Filament/Panels/Admin/Resources/PreferenceResource/` exists (read-only)
  - [ ] BrandResource shows owner, base currency, feature flags
  - [ ] PreferenceResource shows polymorphic owner

- Definition of Done:
  - [ ] admin can CRUD brands, view preferences
  - [ ] full suite green

- Blockers:  none (depends on INFRA-01)
- Notes: Brand is the tenant core — deletion cascades handled by model.
```

---

```
### TICKET CORE-02 — BarberAffiliation resources (Admin + Branch panel)

- Type:           feature
- Universe/area:  admin panel, branch panel

- Implements (doc IDs):
  - Entity:       06 -> BarberAffiliation
  - House rules:  none
  - Flow:         none
  - Edge cases:   none

- Applicable skills: coding-standards, filament-module-structure

- Acceptance criteria:
  - [ ] `modules/BarberAffiliation/Filament/Panels/Admin/Resources/BarberAffiliationResource/` exists
  - [ ] `modules/BarberAffiliation/Filament/Panels/Branch/Resources/AffiliationResource/` exists
  - [ ] Shows barber, affiliable (polymorphic), status, timestamps

- Definition of Done:
  - [ ] admin views all affiliations; branch views own affiliations
  - [ ] full suite green

- Blockers:  none (depends on INFRA-01, AUTH-02, AUTH-03)
- Notes: branch panel scopes by branch_id via middleware.
```

---

```
### TICKET CORE-03 — OfferedService resources (Admin + Barber + Branch)

- Type:           feature
- Universe/area:  admin panel, barber panel, branch panel

- Implements (doc IDs):
  - Entity:       06 -> OfferedService
  - House rules:  none
  - Flow:         none
  - Edge cases:   none

- Applicable skills: coding-standards, filament-module-structure

- Acceptance criteria:
  - [ ] `modules/OfferedService/Filament/Panels/Admin/Resources/OfferedServiceResource/` exists
  - [ ] `modules/OfferedService/Filament/Panels/Barber/Resources/MyServiceResource/` exists
  - [ ] `modules/OfferedService/Filament/Panels/Branch/Resources/ServiceResource/` exists
  - [ ] Admin sees all; barber sees own (serviceable_type=barber); branch sees own

- Definition of Done:
  - [ ] all three panels show correct scoped services
  - [ ] full suite green

- Blockers:  none (depends on INFRA-01, AUTH-02, AUTH-03)
- Notes: polymorphic owner — scoping middleware must check serviceable_type + serviceable_id.
```

---

```
### TICKET CORE-04 — Chair resources (Admin + Branch panel)

- Type:           feature
- Universe/area:  admin panel, branch panel

- Implements (doc IDs):
  - Entity:       06 -> Chair
  - House rules:  none
  - Flow:         none
  - Edge cases:   none

- Applicable skills: coding-standards, filament-module-structure

- Acceptance criteria:
  - [ ] `modules/Chair/Filament/Panels/Admin/Resources/ChairResource/` exists
  - [ ] `modules/Chair/Filament/Panels/Branch/Resources/ChairResource/` exists
  - [ ] Admin sees all chairs; branch sees own (branch_id scoped)

- Definition of Done:
  - [ ] admin manages all chairs; branch manages own chairs
  - [ ] full suite green

- Blockers:  none (depends on INFRA-01, AUTH-03)
- Notes: belongs to Branch — scoping middleware filters by branch_id.
```

---

```
### TICKET TRAN-01 — Booking resources (Admin + Barber + Branch)

- Type:           feature
- Universe/area:  admin panel, barber panel, branch panel

- Implements (doc IDs):
  - Entity:       06 -> Booking, BookingService (pivot)
  - House rules:  04 -> E1, E2, B3 (booking invariants — read-only in Filament)
  - Flow:         none (bookings are created via API, not Filament)
  - Edge cases:   none (read-only in admin; concurrency handled by API)

- Applicable skills: coding-standards, filament-module-structure

- Acceptance criteria:
  - [ ] `modules/Booking/Filament/Panels/Admin/Resources/BookingResource/` exists
  - [ ] `modules/Booking/Filament/Panels/Barber/Resources/MyBookingResource/` exists
  - [ ] `modules/Booking/Filament/Panels/Branch/Resources/BookingResource/` exists
  - [ ] Admin sees all bookings; barber sees own; branch sees own

- Definition of Done:
  - [ ] all three panels show correct scoped bookings
  - [ ] full suite green

- Blockers:  none (depends on INFRA-01, AUTH-02, AUTH-03)
- Notes: read-only in Filament (bookings created via API). Shows services pivot table as relation manager.
```

---

```
### TICKET TRAN-02 — Review resources (Admin + Barber + Branch)

- Type:           feature
- Universe/area:  admin panel, barber panel, branch panel

- Implements (doc IDs):
  - Entity:       06 -> Review
  - House rules:  none
  - Flow:         none
  - Edge cases:   none

- Applicable skills: coding-standards, filament-module-structure

- Acceptance criteria:
  - [ ] `modules/Review/Filament/Panels/Admin/Resources/ReviewResource/` exists
  - [ ] `modules/Review/Filament/Panels/Barber/Resources/MyReviewResource/` exists
  - [ ] `modules/Review/Filament/Panels/Branch/Resources/ReviewResource/` exists
  - [ ] Shows rating, comment, author/subject polymorphic links

- Definition of Done:
  - [ ] all three panels show correct scoped reviews
  - [ ] full suite green

- Blockers:  none (depends on INFRA-01, AUTH-02, AUTH-03)
- Notes: two-way polymorphic — author/subject. Scoping: barber sees reviews of their bookings.
```

---

```
### TICKET TRAN-03 — JobPosting + Application resources (Admin + Branch)

- Type:           feature
- Universe/area:  admin panel, branch panel

- Implements (doc IDs):
  - Entity:       06 -> JobPosting, Application
  - House rules:  none
  - Flow:         none
  - Edge cases:   none

- Applicable skills: coding-standards, filament-module-structure

- Acceptance criteria:
  - [ ] `modules/JobPosting/Filament/Panels/Admin/Resources/JobPostingResource/` exists
  - [ ] `modules/JobPosting/Filament/Panels/Admin/Resources/ApplicationResource/` exists
  - [ ] `modules/JobPosting/Filament/Panels/Branch/Resources/JobResource/` exists
  - [ ] `modules/JobPosting/Filament/Panels/Branch/Resources/ApplicationResource/` exists
  - [ ] Admin sees all; branch sees own jobs and their applications

- Definition of Done:
  - [ ] admin manages all job postings + applications; branch manages own
  - [ ] full suite green

- Blockers:  none (depends on INFRA-01, AUTH-03)
- Notes: Application has profile snapshot (JSON). Branch panel scopes by branch_id.
```