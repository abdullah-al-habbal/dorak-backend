# 03 — Persona Journeys (Day‑in‑the‑Life Stories)

> These are **real‑life stories**, not feature lists. They show how each person actually moves through their day and where Dorak fits. Names are illustrative.
> Personas summary table: `02_prd.md` §3. Rules behind these behaviors: `04_house-rules.md`.

---

## The Client Story

### Story A — Sami (the Grooming universe)

Sami finishes work in Damascus and wants a fresh haircut and beard line before a wedding tomorrow. Transport across town is expensive, so a wasted trip is the last thing he wants.

He opens Dorak. On his very first launch he had chosen his world — **Men's Grooming** — so the app already speaks his language (Arabic) and shows him a charcoal, masculine theme. His home screen lists the **nearest barbershops first**, each with distance and whether seats are open.

He taps the closest one. Instead of a boring dropdown, he sees a **top‑down map of the shop**: a few chairs drawn as simple shapes. **Two are green** — free right now. He taps a green chair and sees it belongs to **Ahmad**, rated 4.9, with a short menu: *haircut, beard trim, hot towel.* Sami likes a barber he trusts, so he could also have searched for Ahmad directly and booked him at whichever branch Ahmad is working today.

He picks **haircut + beard**, sees the price shown in **SYP with the USD equivalent** underneath (the shop turned on dual display), confirms a time slot, and books. The chair he chose is now locked to him — **nobody else can grab that exact seat and time.** He travels once, sits down once.

Afterward, the app asks him to **rate the shop**. He gives 5 stars. The shop, in turn, can rate him as a client — so good clients build a good reputation too.

### Story B — Layla (the Beauty universe)

Layla wants makeup and hair for an engagement party. On first launch she chose **Women's Beauty**, so her app is elegant and blush‑toned, and she only ever sees **women's and unisex** salons.

She's new to her neighborhood, so she **searches** "هير ستايل" in the bar. Results show **branches near her** whose name, brand, or tags match — and also a couple of **freelance stylists** who do **at‑home** visits. One at‑home stylist, **Rasha**, can come to her flat. Layla taps Rasha, picks an at‑home package, and at checkout **shares her location** so Rasha knows where to go. (For a salon visit she'd have used the floor plan instead.)

Layla books, gets a confirmation, and later leaves a review. Two journeys, two universes, one app.

---

## The Salon Owner Story — Khaled (Brand Owner, one shop → three)

Khaled owns a respected men's barbershop. Today it's one location; he dreams of a small chain.

**Day one with Dorak.** Khaled signs up and creates his **Brand**. He doesn't think about "branches" — but the system quietly creates his **first branch** for him (Branch‑First). He fills in the **storefront**: bilingual name, address on the map, opening hours, and tags it **men_only**. He adds **four chairs**, dragging—well, placing—them so the on‑screen floor matches his real shop, and lists his **services with prices** in his base currency.

Within an hour his shop has a real digital storefront, and clients nearby start to see it.

**Three months later — he opens a second shop.** This is the moment that used to mean rebuilding everything. In Dorak it's a single action: **add a Branch** under the same Brand. To do it he needs the **multi‑branch** feature, so he upgrades to **Premium** — and that's the *only* thing he pays for that he didn't have free. **All his history, reviews, and reputation stay attached to the brand.** Nothing is migrated, nothing is lost.

He now sees **both branches in one dashboard**, each with its own manager, hours, seats, and bookings. Six months on he opens a third the same way.

**He needs a new barber.** Rather than asking around, Khaled **posts a simple job** from one branch (this needs the **job board** feature, also Premium). A few barbers tap **Apply**, and a snapshot of each one's profile lands on his dashboard. He also browses independent barbers and **invites** a promising one to join — the barber gets the invite and **accepts**. No paperwork suite, just a clean link.

Khaled's headline benefit isn't "appointments." It's **operational control** that scales.

---

## The Branch Manager Story — Nour (runs one location)

Nour manages Khaled's busiest branch. She doesn't care about the whole brand — she cares about **today, here**.

She opens the admin panel scoped to **her branch only**. She sees the **floor plan** with live chair status, the day's **bookings**, and which **barbers** are affiliated and working. When a chair needs repair, she flips it to **maintenance**, and clients immediately stop being able to book it. When the shop is unusually busy, she adjusts **today's hours**. She can confirm or close out bookings as clients come and go.

What Nour **cannot** do is the brand‑wide stuff — she can't open new branches, change the brand's billing, or touch other branches. Her power is **deep but local** (exact split is an Open Decision; baseline in `04_house-rules.md`).

---

## The Independent Stylist Story — Rasha (freelancer + at‑home)

Rasha is a talented stylist who doesn't want to be tied to one salon. She's a **standalone** professional on Dorak.

She builds **her own profile**: portfolio photos, a bilingual bio, and **her own service menu** with prices. She marks herself a **freelancer** and turns on **at‑home** service with a travel radius around her area. Now she shows up in **client search** even though she has **no fixed building** — clients find her by name or service and book her to come to them, sharing their location at checkout.

A few weeks later, a salon owner is impressed by her portfolio and **invites her to affiliate** with their brand. Rasha **accepts** — and now she also appears **inside that shop's floor plan**, assigned to a chair on the days she works there. Crucially, she's affiliated with **two different shops** at once, *and* still takes her own freelance at‑home clients. Her profile, portfolio, and services are **hers** — if she ever leaves a shop, none of that disappears; only the **affiliation link** ends.

She also keeps an eye on the **job board**. When a good shop posts a role, she taps **Apply**, and her profile snapshot goes straight to that owner. For Rasha, Dorak is at once her **storefront**, her **booking system**, and her **career hub** — without ever surrendering her independence.

---

## What these stories prove (the threads)

- **Branch‑First** makes Khaled's expansion a non‑event — *no migration, no lost reputation.*
- **Barber‑Standalone** lets Rasha be freelance, at‑home, **and** affiliated with multiple shops, owning her data throughout.
- **Backend‑driven visual floor** turns Sami's booking from a dropdown into a tap on a real chair.
- **Two universes** keep Sami and Layla in worlds tailored to them, while unisex shops serve both.
- **Currency + bilingual** make prices and language feel native to every user.
- **Two‑way reviews** build trust on *both* sides of the chair.
