# 04 — House Rules (The Business Laws)

> The **unshakeable laws** of how Dorak operates, written as simple **If / Then** statements. The test: *if a child can understand the rule, it's written correctly.*
> These freeze product logic. They are the source of truth for behavior; entities are in `06_entity-model-abstract.md`, flows in `07_user-flows.md`, hard situations in `08_edge-cases.md`.

---

## A. Brands & branches (the Branch‑First law)

- **A1.** If someone creates a Brand, then the system also creates **exactly one Branch** for it automatically.
- **A2.** If a Brand exists, then it **always has at least one Branch** — there is no Brand with zero branches, ever.
- **A3.** If a Brand wants a **second** Branch, then the **multi‑branch** feature must be turned on for that Brand.
- **A4.** If a Brand grows from one Branch to many, then **no old data is moved or lost** — new branches are simply *added* under the same Brand.
- **A5.** If a Branch is created, then it must have a **gender category**: men‑only, women‑only, or unisex.
- **A6.** If a Brand is deleted, then **its branches and chairs are deleted with it** — but **barbers are not** (barbers are independent; see Group C).

---

## B. Chairs & seats

- **B1.** If a Chair exists, then it **belongs to exactly one Branch**.
- **B2.** If a Chair is shown on the floor plan, then its color reflects its **current status**: green = free now, otherwise not free.
- **B3.** If a Chair's status is **maintenance**, then **no one can book it**.
- **B4.** If a Chair has a barber linked, then tapping it shows **that barber and their services**.
- **B5.** If a Chair has **no** barber linked, then tapping it shows the shop's general services (or an "unassigned" state).
- **B6.** If a Branch has no chairs yet, then clients can see the storefront but **cannot book a chair** there.

---

## C. Barbers & affiliations (the Standalone law)

- **C1.** A Barber is an **independent person**, not a part of any shop.
- **C2.** If a Barber has no shop, then they can **still exist, have a profile, and offer their own services** (including at‑home).
- **C3.** If a Barber joins a shop, then this is recorded as an **affiliation link** — the Barber may be linked to **a whole Brand** or to **a specific Branch**.
- **C4.** If a Barber is talented and busy, then they may be affiliated with **more than one** Brand or Branch at the same time.
- **C5.** If a Barber **leaves** a shop, then **only the affiliation ends** — the Barber's profile, portfolio, and personal services **stay intact**.
- **C6.** If a Barber is linked to a Chair, then that link is for **working at that station** — it does not make the shop own the Barber.
- **C7.** If a Barber marks themselves a **freelancer**, then they can offer **at‑home** service within a travel radius.

---

## D. Services & pricing

- **D1.** If a Service exists, then it is owned by **either a Brand or a Barber** — never floating with no owner.
- **D2.** If a Service has a price, then that price is stored in **one chosen currency**.
- **D3.** If a Brand exists, then it has **one base currency**.
- **D4.** If a client views a price in a different currency, then the system **converts it on the fly** using the current exchange rate — prices are **never hard‑coded** across currencies.
- **D5.** If a shop chooses dual display, then the client sees **both** the base price and the converted price.
- **D6.** If a Service is marked **at‑home**, then booking it captures the **client's location** instead of a chair.

---

## E. Bookings (the no‑double‑booking law)

- **E1.** If two clients try to take the **same chair at the same time**, then **exactly one** succeeds and the other is told **"this seat was just taken."**
- **E2.** If a client books a chair + time slot, then that exact chair + slot is **locked** to them.
- **E3.** If a client prefers a specific Barber, then they may book **by the Barber** instead of by the chair.
- **E4.** If a booking is for an **at‑home** Barber, then it uses the **client's shared location**, not a floor plan.
- **E5.** If a Chair becomes **maintenance** after someone booked it, then those existing bookings must be **handled, not silently dropped** (see `08_edge-cases.md`).

---

## F. Reviews (two‑way trust)

- **F1.** If an appointment is **completed**, then **both sides may review**: client → shop, and shop → client.
- **F2.** If no appointment happened, then **no review** can be left for it.
- **F3.** If a client behaves badly, then the shop's review **protects other shops**; if a shop gives poor service, the client's review **protects other clients**.

---

## G. Jobs & hiring (simple, not an ATS)

- **G1.** If a Brand wants to post a job, then the **job board** feature must be turned on.
- **G2.** If a job is posted, then it is simply **open or closed** — for MVP there is **no paid‑vs‑unpaid** distinction.
- **G3.** If a Barber taps **Apply**, then a **snapshot of their profile** is sent to the shop's dashboard — nothing more complex.
- **G4.** If a shop **invites** a Barber, then the Barber must **accept or reject** before any affiliation becomes active.
- **G5.** If an invite is sent, then its state is **pending** until answered.

---

## H. Permissions & roles (RBAC)

> Baseline; the exact Owner‑vs‑Manager split is an **Open Decision** (`02_prd.md` §8).

- **H1.** A **Brand Owner** can manage **everything in their Brand**: all branches, billing/features, branch managers, jobs, and affiliations.
- **H2.** A **Branch Manager** can manage **only their own branch**: its chairs, hours, bookings, and day‑to‑day staff presence.
- **H3.** A Branch Manager **cannot** open new branches, change brand billing, or touch other branches.
- **H4.** A **Barber** can manage **their own** profile, portfolio, and personal services, and respond to invites/jobs.
- **H5.** A **Client** can manage **their own** profile, bookings, and reviews.
- **H6.** A **Platform Admin** can manage tenants, currencies, exchange rates, and feature flags across the platform.
- **H7.** No role can ever see or change **another Brand's** data (multi‑tenant isolation).

---

## I. Feature gating (Freemium)

- **I1.** If a feature is **free** (micro‑site, hours, location, single‑chair discovery, basic booking), then **every** Brand has it.
- **I2.** If a feature is **premium** (multi‑branch, job board, and later the floor‑plan designer), then it works **only when its flag is on**.
- **I3.** If a Brand **downgrades** while holding multiple branches, then it must be handled gracefully — extra branches are **not destroyed** (see `08_edge-cases.md`).
- **I4.** If a user is a **freelance Barber**, then **salon‑only gates** (like multi‑branch) **do not apply to them** at all.
- **I5.** If a flag changes, then the change takes effect **the moment the next action is attempted** (flags are read at action time).

---

## J. Language & universes

- **J1.** If any name or description is shown, then it should appear in the **user's language (Arabic or English)**.
- **J2.** If a translation is **missing**, then the system shows the **other language as fallback** — never an empty label.
- **J3.** If a client opens the app for the first time, then they choose a **universe**: Men's Grooming or Women's Beauty.
- **J4.** If a client is in a chosen universe, then discovery **defaults to matching shops** for that universe.
- **J5.** If a shop is **unisex**, then it appears in **both** universes.

---

## K. Value Objects (the No-Raw-JSON law)

> Enforced by `11_backend-architecture.md` §5 and implemented in `{Module}/ValuesObjects/`.

- **VO-1.** If a database column stores structured data, then its PHP representation **must** be a `final readonly` Value Object in `{Module}/ValuesObjects/`. Never an untyped `array`.
- **VO-2.** If a Value Object is cast from JSON, then the cast class **must** validate all keys and throw `InvalidArgumentException` on malformed data. No silent defaults.
- **VO-3.** If a Value Object contains enums, then the enum **must** be a Backed Enum and validated via `::tryFrom()` during hydration.
- **VO-4.** If a model has a nullable Value Object field, then the cast **must** return `null` for `null` database values — never a default/empty Value Object.
- **VO-5.** Cross-module Value Object sharing is **prohibited**. If `ClientInteraction` needs a `PriceRangeValueObject`, it defines its own or uses a primitive. No `use Modules\ServiceCatalog\...` in `ClientInteraction` Value Objects.

---

## L. Client Intelligence (the Catalog & Personalization law)

> Rules migrated from `prd.md` §8. See also `02_prd.md` §12 and `06_entity-model-abstract.md` §6.

- **CAT-1.** If a `ServiceCatalogItemModel` exists, then `OfferedServiceModel` **may** reference it via `catalog_item_id`, but is **not required** to. Free-text services remain valid.
- **CAT-2.** If a `ServiceCatalogItemModel` is deactivated (`is_active = false`), then existing `OfferedServiceModel` references remain valid, but new references are blocked.
- **HIST-1.** If a booking status transitions to `completed`, then a `ClientServiceHistoryModel` record **must** be auto-generated inside the same database transaction.
- **HIST-2.** If a client deletes their account, then `ClientServiceHistoryModel` records are **anonymized** (`client_id` set to `null`) but retained for aggregate recommendation integrity.
- **HIST-3.** A barber has **full read access** to a client's `ClientServiceHistoryModel` when that client has an active or upcoming booking with them. Access is read-only and scoped to the booking context.
- **FACE-1.** If a client uploads a face photo, the photo is **never** publicly visible. It is used solely for AI analysis and private recommendations.
- **FACE-2.** A client may upload **minimum 0, maximum 5** face photos. One may be designated `is_primary`.
- **FACE-3.** If a client deletes their account, face photos are purged after a **30-day grace period**.
- **INT-1.** If a client interacts with a discoverable entity, the `ClientInteractionLogModel` **must** capture the active universe and applied filters in the `context` JSON.
- **FAV-1.** `ClientFavoriteModel` records are **strictly private**. No user can see another user's favorites, and no public "favorite count" is displayed.
- **REC-1.** If a `RecommendationEdgeModel` weight is computed, it **must** include a `computed_at` timestamp and a `vector_version` so stale edges can be invalidated by future recomputation jobs.
- **REC-2.** Recommendation vector and edge recomputation runs as a **nightly batch job**, not real-time on every search query.
