# Dorak Backend — Product Documentation

This folder is the **single source of truth** for the product *before any backend or database design begins*. It contains **no code, no SQL, and no real database ERD** — entities are described in **plain English as objects with attributes**, and an **abstract** relationship view is provided where diagrams help understanding.

---

## What this documentation is (and is not)

| It **is** | It is **not** |
|---|---|
| Product truth (the "what" and "why") | Implementation (the "how in code") |
| Plain‑English entities + abstract relationships | A real database schema or migrations |
| Business laws frozen as simple rules | A spec that locks the team into one tech choice |
| A living, maintainable reference | A one‑time document that goes stale |

---

## Reading order

The documents move from **"What problem are we solving?"** → **"Who does what?"** → **"What are the laws?"** → **"How is the system shaped?"**

| # | File | Purpose | Audience |
|---|---|---|---|
| — | [`README.md`](./README.md) | This index | Everyone |
| 01 | [`01_vision-and-scope.md`](./01_vision-and-scope.md) | Vision, problem, MVP boundary, what we will **not** build now | Everyone |
| 02 | [`02_prd.md`](./02_prd.md) | **Master document.** Goals, scope, features, resolved & open decisions, metrics, risks | Product, Eng, Stakeholders |
| 03 | [`03_persona-journeys.md`](./03_persona-journeys.md) | Day‑in‑the‑life stories for each persona | Product, Design |
| 04 | [`04_house-rules.md`](./04_house-rules.md) | The unshakeable business laws as simple If / Then statements | Everyone |
| 05 | [`05_domain-glossary.md`](./05_domain-glossary.md) | The bounded vocabulary — one agreed meaning per term | Everyone |
| 06 | [`06_entity-model-abstract.md`](./06_entity-model-abstract.md) | Entities as plain‑English objects + abstract relationships + **build priority order** | Eng, Product |
| 07 | [`07_user-flows.md`](./07_user-flows.md) | Step‑by‑step interaction flows (diagrams) | Eng, Design |
| 08 | [`08_edge-cases.md`](./08_edge-cases.md) | The hard situations and how the product behaves | Eng, QA, Product |
| 09 | [`09_c4-context.md`](./09_c4-context.md) | C4 Level 1 — the system as one box and who/what touches it | Eng, Stakeholders |
| 10 | [`10_c4-containers.md`](./10_c4-containers.md) | C4 Level 2 — the major moving parts inside the system | Eng |
| 11 | [`11_backend-architecture.md`](./11_backend-architecture.md) | C4 Level 3 — module layout + the **Action → Handler → EloquentResolver** pattern + coding conventions | Eng |

The **PRD (02)** is the master. Everything else supports and expands it.

> **Beyond the spec:** the executable wrapper around these docs lives in `../.claude/skills/` (the Laravel baseline skills). See `.claude/skills/README.md`.

---

## Core principles (the four invariants)

These appear throughout the docs. If you read nothing else, read these.

1. **Branch‑First** — even a single standalone shop is stored as a **Brand that owns exactly one Branch**. Nobody is ever "just a salon". This means growing from one shop to many is *adding a branch*, never a painful data migration.
2. **Barber is standalone** — a barber is an **independent entity**, not a child of a salon. A barber can be a freelancer (including **at‑home** service), and can be linked to one *or many* brands/branches through a flexible affiliation layer.
3. **Backend‑driven UI** — the visual shop floor (clickable chairs, green = free now) is **described by the backend** and simply *drawn* by the app. The look is good, but it is generated, not hand‑built per shop.
4. **Two universes** — the consumer experience is split into **Men's Grooming** and **Women's Beauty**, with **unisex** shops appearing in both where relevant.

---

## Status legend (used inside the docs)

- ✅ **Decided** — agreed; build to this.
- 🟡 **Open** — a decision still owed by the product owner (see the "Open Decisions" section of the PRD).
- 🔭 **Later** — intentionally deferred (offline mode, map localization, advanced analytics, etc.).

---

## A note on scope discipline

Several capabilities raised during brainstorming are **intentionally postponed** so the MVP stays shippable: **offline‑first mode, map provider localization, SMS/WhatsApp gateway engineering, advanced analytics, complex staff shifts, and the paid‑vs‑unpaid job distinction.** They are recorded as 🔭 **Later** and revisited *after* the core engine is live. See [`01_vision-and-scope.md`](./01_vision-and-scope.md).
