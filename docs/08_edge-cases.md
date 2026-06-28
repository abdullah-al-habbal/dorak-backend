# 08 — Edge Cases

> The hard situations. Each one states the **scenario**, the **desired behavior**, and whether it's ✅ decided or 🟡 open. Rules referenced as `[E1]` point to `04_house-rules.md`.

---

## Bookings & concurrency

### EC‑1 — Two clients tap the same green chair at once ✅
**Scenario:** Two people select the same chair + slot within the same instant.
**Behavior:** **Exactly one** booking succeeds; the other immediately sees **"this seat was just taken"** and returns to the floor plan `[E1]`. This is a **hard guarantee** (NFR in `02_prd.md` §9). *How* it's enforced is an engineering concern, not part of these docs.

### EC‑2 — Chair flips to maintenance after bookings exist ✅(policy)
**Scenario:** A chair already has future bookings, then is set to **maintenance**.
**Behavior:** Existing bookings must be **handled, not silently dropped** `[E5]`: notify affected clients and offer rebooking (another chair/barber/time). 🟡 *exact reaccommodation UX is open.*

### EC‑3 — No‑show or late cancellation 🟡
**Scenario:** Client books but doesn't arrive, or cancels at the last minute.
**Behavior:** Booking moves to **no‑show / cancelled**; the slot frees up. 🟡 *cancellation window, penalties, and whether reputation is affected are Open Decisions (`02_prd.md` §8 #6/#7).*

### EC‑4 — Booking "by barber" when that barber moves chairs ✅(principle)
**Scenario:** A client booked a specific **barber**, but the barber's station changes that day.
**Behavior:** The booking follows the **barber**, not the chair `[E3]`. The floor plan reflects the barber's actual station.

---

## Growth & lifecycle

### EC‑5 — Single shop becomes multi‑branch ✅
**Scenario:** A standalone shop opens a second location.
**Behavior:** Because of **Branch‑First**, this is just **add a Branch** under the same Brand — **no migration, no lost data, no lost reputation** `[A1–A4]`. This is the headache the whole invariant exists to prevent.

### EC‑6 — Premium downgrade while holding multiple branches ✅(policy)
**Scenario:** A brand on Premium (multi‑branch) downgrades to Free.
**Behavior:** Extra branches are **never destroyed** `[I3]`. They should be **suspended/read‑only** (still visible, not actively manageable) until the brand re‑upgrades. 🟡 *exact "locked" behavior to confirm.*

### EC‑7 — Deleting a Brand ✅
**Scenario:** A Brand is removed.
**Behavior:** Its **Branches and Chairs go with it** `[A6]`. **Barbers survive** — only their **affiliations** to that brand end `[C5]`. Independent barber data is never collateral damage.

---

## Standalone barbers & affiliations

### EC‑8 — Barber affiliated with several shops at once ✅
**Scenario:** A popular barber works for two brands and freelances at home.
**Behavior:** Multiple **active affiliations** coexist `[C4]`, plus independent at‑home work. Each affiliation is its own link with its own status.

### EC‑9 — Schedule conflict across affiliations 🟡
**Scenario:** The same barber is double‑booked across **two different shops** (or a shop + at‑home) for overlapping times.
**Behavior:** The system should prevent a barber from being committed to two places at once. 🟡 *whether MVP enforces a unified barber calendar across affiliations, or treats each shop's calendar separately, is an Open Decision (relates to `02_prd.md` §8 #5).*

### EC‑10 — Barber leaves a shop ✅
**Scenario:** An affiliation is **terminated**.
**Behavior:** The barber's **profile, portfolio, and personal services stay** `[C5]`; the chair they used becomes **unassigned**; their future shop bookings are handled like EC‑2. The barber keeps freelancing if they wish.

### EC‑11 — Freelancer with no fixed location in search ✅
**Scenario:** An at‑home‑only barber has no building/coordinates.
**Behavior:** They still appear in **search** by name/service (the unified search surfaces freelancers), and booking uses the **client's** location, not the barber's `[E4]`. They simply don't anchor to the proximity map the way branches do.

---

## Universes & unisex

### EC‑12 — Unisex shop and the universe filter ✅
**Scenario:** A unisex salon should reach both audiences.
**Behavior:** It appears in **both** the Men's Grooming and Women's Beauty universes `[J5]`.

### EC‑13 — Separate men's/women's sections in one unisex shop 🟡
**Scenario:** A unisex shop physically has distinct men's and women's areas.
**Behavior:** The dashboard should let the shop represent these sections. 🟡 **Open Decision** (`02_prd.md` §8 #2): two floor plans vs. zones within one plan.

---

## Money

### EC‑14 — Exchange rate changes between viewing and paying ✅(principle)
**Scenario:** A client sees a converted price, then books moments later after the rate moved.
**Behavior:** Prices are **computed on the fly from the current rate** `[D4]`; the displayed converted value is **indicative**. The **base‑currency price is the source of truth**. 🟡 *whether to "freeze" a quoted rate at booking time is a future refinement.*

### EC‑15 — Missing exchange rate for a currency pair ✅(policy)
**Scenario:** No rate exists to convert into the client's display currency.
**Behavior:** Fall back to showing the **base‑currency price** (never show a wrong or blank price).

---

## Language

### EC‑16 — Missing translation for a name/description ✅
**Scenario:** A shop filled Arabic but not English (or vice‑versa).
**Behavior:** Show the **available language as fallback** `[J2]` — a label is **never empty**.

---

## Chairs, jobs, and reviews

### EC‑17 — Branch with zero chairs ✅
**Scenario:** A storefront exists but no chairs are defined yet.
**Behavior:** The branch is **visible** but **not chair‑bookable** `[B6]` (an at‑home affiliated barber could still be booked by barber/location).

### EC‑18 — Reviewing without a completed appointment ✅
**Scenario:** Someone tries to review a shop they never actually visited.
**Behavior:** **Not allowed** — reviews require a **completed** booking `[F2]`.

### EC‑19 — Duplicate or similar brand/branch names ✅(principle)
**Scenario:** Several "Royal Barber" branches exist across cities.
**Behavior:** Search matches **branch + brand + tags** and ranks by **proximity**, so the client distinguishes by **distance and details**, not by name alone `[search]`.

### EC‑20 — Job posted on a branch that later closes/deletes 🟡
**Scenario:** A job's branch is removed while the job is open.
**Behavior:** Open jobs for a removed branch should be **closed automatically**; existing applications remain visible to the owner. 🟡 *confirm desired handling.*

---

## Identity

### EC‑21 — One person is both a Client and a Barber 🟡
**Scenario:** A barber also books appointments as a client elsewhere.
**Behavior:** One **User** can carry both a Client and a Barber profile. 🟡 **Open Decision** (`02_prd.md` §8 #8): confirm this is allowed and how the app switches context.

---

## Summary of what's still open 🟡

EC‑3 (cancellation policy), EC‑6 (downgrade lock UX), EC‑9 (cross‑affiliation calendar), EC‑13 (unisex sectioning), EC‑14 (rate freezing), EC‑20 (job on deleted branch), EC‑21 (dual Client/Barber identity). All map back to **`02_prd.md` §8 — Open Decisions**.