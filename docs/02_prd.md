# 02 — Product Requirements Document (PRD)

> **This is the master document.** All other files support and expand it.
> Product: **Kraseena** (placeholder name — see `01_vision-and-scope.md`).
> Scope of this PRD: the **MVP** plus the immediate phases, with decisions frozen and open questions flagged.

---

## 1. Product summary

Kraseena is a **multi‑tenant SaaS platform** for the Syrian grooming and beauty industry. It gives **owners** an operational management engine (storefront, seats, hours, staff, jobs) and gives **clients** a **visual, location‑aware discovery and booking** experience across two universes — **Men's Grooming** and **Women's Beauty**. Independent **barbers/stylists** are first‑class and can operate freelance (including **at‑home**) or affiliate with shops.

The product is anchored on four invariants — **Branch‑First, Barber‑Standalone, Backend‑Driven UI, Two Universes** — defined in `01_vision-and-scope.md`.

---

## 2. Goals & non‑goals

### Goals (MVP)
1. Let any shop create a **branch storefront** (profile, hours, location, services, prices) in minutes.
2. Let clients **discover the nearest** relevant shops and **book against a visual floor plan**.
3. Make **growth painless**: one shop → many branches with zero data migration (Branch‑First).
4. Treat **barbers as independent**: freelance, at‑home, and/or affiliated with shops.
5. Support **bilingual** content and **multi‑currency** pricing under volatile exchange rates.
6. **Never** allow a chair/time‑slot to be double‑booked.

### Non‑goals (now) 🔭
- Offline‑first behavior, map‑provider localization, SMS/WhatsApp gateway engineering.
- Advanced analytics, complex shift scheduling, paid‑vs‑unpaid job mechanics, commission/payment processing.
- A full applicant‑tracking system (ATS) or LinkedIn‑style network.
- In‑app payments / wallet (booking is a *reservation*, not a transaction, for MVP).

---

## 3. User types (personas)

Full narratives in `03_persona-journeys.md`; permission rules in `04_house-rules.md`.

| Persona | Who they are | Primary need | Lives in |
|---|---|---|---|
| **Client** | A man or woman seeking grooming/beauty | Find a nearby shop, see who's free, book without waiting | Mobile app |
| **Brand Owner** | Owns the business (1 → many branches) | Operational control across all branches; grow without pain | Admin panel |
| **Branch Manager** | Runs day‑to‑day at one branch | Manage that branch's seats, hours, staff, bookings | Admin panel |
| **Barber / Stylist** | Independent professional | Own profile & services; work freelance/at‑home and/or for shops; find work | Mobile app (+ light panel) |
| **Platform Admin** | Kraseena operator | Onboard tenants, moderate, manage currencies & flags | Admin panel |

---

## 4. MVP scope (detailed)

### 4.1 Storefront & hierarchy ✅
- Create a **Brand**; the system auto‑creates **exactly one Branch** (Branch‑First).
- Branch carries: bilingual name, **gender category** (`men_only` / `women_only` / `unisex`), location (coordinates), opening/closing hours, settings.
- Define **chairs/seats** for the branch, each with a position for the visual floor plan and a status (`available` / `occupied` / `maintenance`).
- Define **services** (bilingual name, price, currency, optional `at_home`).

### 4.2 Discovery & search ✅
- Client **home feed** ranks shops by **proximity**, filtered by the chosen **universe**.
- **Search bar queries Branches first** (a client visits a *building*, not a corporation), matching **branch name + brand name + tags** together, and **also surfaces freelance barbers** (who may have no fixed address). See [§7 Resolved Decisions](#7-resolved-decisions).

### 4.3 Visual floor plan + booking ✅
- Opening a branch shows a **top‑down floor plan**: abstract shapes + chairs colored by live status (**green = free now**).
- Tapping a free chair reveals the **linked barber** (if any) and the relevant **services**, then opens a **booking** for a time slot.
- A client who prefers a **specific barber** can book **by that barber** rather than by chair.
- **Double‑booking is impossible** (see NFR §9 and `04_house-rules.md`).

### 4.4 Standalone & at‑home barbers ✅
- A barber owns a profile, portfolio, settings, and **their own services**.
- A **freelancer** can offer **at‑home** services with a travel radius; at‑home booking captures the **client's location** instead of a floor‑plan chair. 🟡 *exact checkout flow is an open decision.*

### 4.5 Bilingual & currency ✅
- All user‑facing names/descriptions are **translatable (Arabic + English)** with fallback.
- A **base currency** per brand; services priced in a currency; a global **exchange‑rate** layer converts **on the fly**. A shop may opt to **show both** currencies.

### 4.6 Roles & access ✅
- Brand Owner > Branch Manager > Barber > Client, plus Platform Admin (see `04_house-rules.md`).

### 4.7 Reviews 🟢 (early Phase 2)
- **Two‑way** after a completed appointment: client rates shop; shop rates client.

### 4.8 Simple jobs & invitations 🟢 (early Phase 2)
- A branch can **post a job** (open/closed). A barber taps **Apply** → a snapshot of their profile reaches the owner's dashboard. *No ATS.*
- A shop can **invite** a barber to affiliate; the barber **accepts/rejects**.

---

## 5. Phasing

| Phase | Theme | Includes |
|---|---|---|
| **Phase 1 — Core Engine** ✅ | Make a shop real and bookable | Storefront, Branch‑First hierarchy, seats, geolocation discovery, visual floor plan, booking, standalone/at‑home barbers, bilingual, currency, double‑booking protection, roles |
| **Phase 2 — Trust & Talent** 🟢 | Build trust and the B2B loop | Two‑way reviews, simple job board, employee invitations |
| **Phase 3 — Power & Polish** 🟠 | Depth for serious operators | Advanced analytics, richer barber portfolios, owner‑editable floor‑plan designer |
| **Later** 🔭 | Resilience & growth bets | Offline mode, map localization, richer notifications, paid/unpaid jobs, complex shifts, payments |

---

## 6. Monetization & feature flags

**Model:** Freemium Feature Gate, enforced by per‑brand **feature flags** (a simple set of on/off switches, e.g. `multi_branch`, `job_board`, `floor_plan_designer`).

| Tier | Unlocks |
|---|---|
| **Free** | Micro‑site, basic hours, location, single‑chair discovery, basic booking |
| **Premium** | **Multi‑branch management**, **job‑board posting**, and (Phase 3) **custom floor‑plan designer** |

**Rules of the gate** (full list in `04_house-rules.md`):
- A free brand may hold **only one branch**; creating a second requires the `multi_branch` flag.
- Posting a job requires the `job_board` flag.
- **Freelance barbers cannot access salon‑only gates** (multi‑branch monetization is meaningless for an individual).
- Flags are **read at the moment of action**, so upgrades/downgrades take effect immediately.

---

## 7. Resolved decisions

These were open during brainstorming and are now **✅ decided**.

1. **Search targets Branches (plus freelancers), not Brands.** Clients need distance, seats, and booking for the **specific building** nearest them. The query matches branch + brand + tags, and **unions in freelance barbers** so independents are discoverable even without a fixed address.
2. **Branch‑First invariant.** No "shop without a branch." Growth = add a branch. This eliminates the single→multi‑branch migration entirely.
3. **Barber is a standalone entity** linked to brands/branches through a **flexible affiliation layer** (a barber may affiliate with *many* shops, or none). Leaving a shop never deletes the barber's own data.
4. **Services are owned by either a brand or a barber** (so freelancers have menus too), with an **at‑home** option.
5. **Backend‑driven visual floor plan.** The backend describes the layout; the app draws it. Green = free now; tap → barber + services → book.
6. **Two universes with a gender category** on every branch; unisex appears in both.
7. **Currency engine, not hard‑coded prices.** Base currency + exchange layer + optional dual display.
8. **Double‑booking is structurally impossible** (see NFR §9).
9. **Each Brand, Branch, Barber, and Client owns a Settings record** (language, notifications, display currency, theme/universe, price‑display mode).

---

## 8. Open decisions (owed by the product owner) 🟡

> Listed so they are not forgotten. Each blocks or shapes a piece of build.

1. **At‑home checkout:** when booking an at‑home barber, does the client drop a **map pin / coordinates** at checkout (vs. picking a chair)? What address detail is required?
2. **Unisex floor management:** how does a unisex shop represent **separate men's / women's sections** of its physical floor within one dashboard — two floor plans, or zones within one?
3. **Owner‑editable floor‑plan designer:** build a **drag‑and‑drop** chair editor in the admin panel (Phase 3), or have admins position chairs via simpler inputs for MVP?
4. **Permission granularity:** the exact split of capabilities between **Brand Owner** and **Branch Manager** (e.g., can a manager edit prices? post jobs? manage affiliations?). Baseline proposed in `04_house-rules.md`; confirm.
5. **Live queue vs. static booking:** does MVP show **real‑time seat status** (a chair flips to green/red live) or only **scheduled** bookings? (Affects how "green now" is computed.)
6. **Booking granularity:** fixed time slots vs. service‑duration‑based slots; cancellation/no‑show window.
7. **Review eligibility:** can either side review only **after a completed** appointment? How are disputes/no‑shows handled?
8. **Identity model for barbers:** is every barber also a platform user account from day one, and can one person be **both** a client and a barber?

---

## 9. Non‑functional requirements (NFRs)

- **No double‑booking (hard guarantee).** Concurrent attempts on the same chair + slot must resolve so that **exactly one** succeeds; the loser sees a clear "this seat was just taken." (Implementation note for engineering — *not part of these docs* — is row‑locking inside a transaction; captured here only as a **requirement**.)
- **Bilingual everywhere** with graceful fallback (never show an empty label).
- **Multi‑tenant isolation:** one brand can never see or affect another brand's data.
- **Stable identifiers:** entities use non‑guessable identifiers suitable for public URLs/APIs.
- **Performance for discovery:** proximity search must feel instant on a typical phone.
- **Auditability of affiliations & bookings:** status changes (invite → active → terminated; booked → completed/cancelled) are traceable.
- **Reasonable resilience** for MVP — but **offline mode is explicitly out** for now (🔭).

---

## 10. Risks & mitigations

| Risk | Impact | Mitigation |
|---|---|---|
| Owners resist any subscription | Low supply | Generous **free** tier; charge only for clear‑value gates (multi‑branch, jobs) |
| Currency swings make prices look wrong | Lost trust | **Base currency + live exchange**, optional dual display; prices never hard‑coded |
| Double‑booking embarrasses shops | Churn | Hard structural guarantee (NFR §9) |
| Scope creep from "Later" items | Missed launch | Items quarantined as 🔭 in `01` and `02`; revisit only post‑MVP |
| Cold‑start (empty marketplace) | No demand | Lead with the **management engine**; the marketplace is a byproduct that fills as shops onboard |
| Barber data lost when leaving a shop | Pro distrust | **Standalone barber** invariant; affiliations are links, not ownership |
| Unisex shops confuse the universe filter | Bad UX | Unisex appears in **both** universes; clarify floor sectioning (Open Decision #2) |

---

## 11. Glossary & models

- Vocabulary: `05_domain-glossary.md`
- Entities (plain English) + build priority order + abstract relationships: `06_entity-model-abstract.md`
- Flows: `07_user-flows.md` · Edge cases: `08_edge-cases.md`
- System shape (C4): `09_c4-context.md`, `10_c4-containers.md`