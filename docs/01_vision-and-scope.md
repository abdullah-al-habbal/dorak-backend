# 01 — Vision & Scope

> **Project name:** _Dorak_ (دورك) — "your turn".
> **One line:** _Dorak is the operating system for barber shops and beauty salons — for both men and women — across Syria: a management engine for owners, and a visual discovery & booking app for clients._

---

## 1. The problem

The grooming and beauty industry in Syria runs on memory, paper, phone calls, and physical waiting rooms. This creates four recurring pains:

- **Operational blindness.** Owners cannot easily see their seats, staff, hours, and (when they grow) their multiple branches in one place. Expansion multiplies the chaos.
- **The waiting‑room crisis.** Clients travel to a shop with **no visibility** into whether a chair is free, who is working, or how long they will wait. Transport is costly, so a wasted trip is a real loss.
- **Hiring bottlenecks.** Shops struggle to find vetted stylists/barbers. Independent professionals have no place to be discovered or to find work.
- **No real digital storefront.** Most shops have, at best, a social‑media page that cannot show seats, services, prices, or take a structured booking.

---

## 2. The vision

A **dual‑sided platform** with a clear order of priority:

1. **First, a Store Management Engine for owners.** The headline value is *operational control*: map your shop floor, define seats and hours, manage who works where, post a job. This is what we sell.
2. **Second, a free consumer marketplace** that owners receive *as a byproduct* of using the management software: clients discover nearby shops and book against a **visual, interactive shop floor**.
3. **Third, a lightweight B2B talent layer** connecting shops with barbers/stylists — *simple invitations and applications, not a recruiting suite.*

We are **not** building "yet another booking app." We are building the **digital infrastructure** the industry standardizes on.

---

## 3. Two universes, one platform

To double the addressable market without alienating anyone, the consumer experience splits into two worlds **from the first tap**:

- **The Grooming Universe** — men's barbershops, hair, beard.
- **The Beauty Universe** — women's salons, makeup, specialized care.

Every shop carries a **gender category**: `men_only`, `women_only`, or `unisex`. A **unisex** shop appears in *both* universes. The chosen universe sets the visual theme and **filters discovery by default** so clients see a hyper‑relevant list.

> 🟡 **Open:** how a *unisex* shop represents separate men/women areas of its physical floor inside one dashboard (see PRD → Open Decisions and `08_edge-cases.md`).

---

## 4. The four invariants (non‑negotiable design principles)

These are the spine of the whole product. Detailed consequences live in `06_entity-model-abstract.md` and `04_house-rules.md`.

### 4.1 Branch‑First
Even a single neighborhood shop is stored as a **Brand that owns exactly one Branch**. There is no such thing as a "shop with no branch."

- **Why:** when an owner opens their second location, it is simply **a new Branch under the same Brand** — *no data migration, no lost history, no lost reputation.* The "simple salon → multi‑branch salon" transition is therefore a non‑event.

### 4.2 Barber is standalone
A barber/stylist is an **independent entity**, never merely a row inside a salon.

- A barber can be a **freelancer**, including **at‑home** service (they travel to the client).
- A barber can be **affiliated** to **one or many** brands/branches through a flexible link layer, and can leave without their personal profile, portfolio, or services being destroyed.

### 4.3 Backend‑driven UI
The signature experience — a **top‑down visual floor plan** with abstract shapes and **clickable chairs (green = free right now)** — is **described by the backend** and merely *rendered* by the client app. Tapping a free chair reveals the assigned barber (if any) and their services, then opens booking.

- **Why:** consistent, maintainable, and centrally controllable look across thousands of shops, without bespoke front‑end work per shop.

### 4.4 Two universes
Men's Grooming and Women's Beauty are first‑class, with unisex bridging both (see §3).

---

## 5. Scope — what is in, next, later

This is the MVP boundary. Phased detail lives in `02_prd.md`.

### ✅ MUST HAVE — the Core Engine (MVP)
- **Micro‑site / digital storefront** per branch: profile, hours, location, services, prices.
- **Branch‑First hierarchy:** Brand → Branch → Chair/Seat, with seats defined.
- **Geolocation discovery:** client home feed shows the **nearest** shops; search prioritizes branches.
- **Visual shop floor + booking:** interactive chairs, live availability, tap‑to‑book.
- **Standalone barbers** with their own profile and services, including basic **at‑home** capability.
- **Bilingual** content (Arabic + English) and **multi‑currency** pricing (e.g., SYP, USD) with exchange handling.
- **Double‑booking protection** (a chair/slot can never be taken twice).
- **Roles:** Brand Owner, Branch Manager, Barber, Client (+ Platform Admin).

### 🟢 SHOULD HAVE — the Differentiators (early Phase 2)
- **Two‑way reviews** (client ↔ shop).
- **Simple job board** (post a role; "Apply" routes a profile to the owner — *not* an ATS).
- **Employee invitations** (shop invites a barber to affiliate; barber accepts/rejects).

### 🟠 COULD HAVE — Phase 3
- Advanced analytics panels for owners.
- Richer B2B portfolios for barbers.
- **Owner‑editable** visual floor‑plan designer (drag chairs) inside the admin panel.

### 🔴 WON'T HAVE — at launch
- Paid‑vs‑unpaid job distinction (jobs are simply open/closed for MVP).
- Complex staff shift scheduling.

---

## 6. Explicitly deprioritized (🔭 Later)

Raised during research, **intentionally postponed** so the MVP ships. We revisit these *after* the core engine is live, partly because connectivity in Syria is improving:

- **Offline‑first** dashboard behavior.
- **Map provider localization** (OSM/Mapbox vs others) — use the simplest workable option for now.
- **SMS / WhatsApp gateway engineering** for reminders — keep notifications minimal for MVP.
- **Advanced analytics**, complex shifts, and lead‑generation billing experiments.

> These are not rejected — they are sequenced. Recording them here prevents them from quietly creeping into MVP.

---

## 7. Monetization stance (summary)

Subscription resistance is real for small owners, so the model is a **Freemium Feature Gate**, enforced through **feature flags**:

- **Free:** micro‑site, basic hours, location, single‑chair discovery, basic booking.
- **Premium:** **multi‑branch management**, **job board posting**, and (Phase 3) the **custom floor‑plan designer**.

Freelance barbers must **not** be able to reach salon‑only multi‑branch gates. Detail in `02_prd.md` → Monetization & Feature Flags.

---

## 8. Success metrics (MVP)

- **Supply:** number of branches onboarded; % that complete a floor plan with ≥1 seat.
- **Activation:** % of branches with hours + ≥1 service + ≥1 seat within 7 days.
- **Demand:** weekly active clients; searches → branch views → bookings (the funnel).
- **Core value proof:** completed bookings per active branch per week.
- **Trust loop:** % of completed appointments that receive a two‑way review.
- **Retention:** branch retention at 30 / 60 / 90 days.

---

## 9. Context & constraints

- **Geography:** Syria. Transport cost makes *proximity* a top‑ranked discovery signal.
- **Economy:** currency volatility ⇒ prices live in a chosen **base currency** with **on‑the‑fly exchange**, never hard‑coded amounts.
- **Language:** Arabic‑first audience; full **Arabic + English** throughout, with sensible fallback when a translation is missing.
- **Identity:** the product owner (Abdullah) works in mixed Arabic/English; documentation here is **English**, content/data is **bilingual**.
