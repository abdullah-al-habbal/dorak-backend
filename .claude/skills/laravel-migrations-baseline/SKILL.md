---
name: laravel-migrations-baseline
description: Project standard for writing Laravel database migrations for this salon/barbershop platform. Use whenever creating, editing, or reviewing a migration, adding a table or column, or changing foreign keys / cascade behavior. Enforces that the schema matches the abstract entity model in docs/06 AND the cascade laws in docs/04 (especially A6 and C5) exactly, uses UUID keys and JSON translatable columns, models the three polymorphic links correctly, and is reversible without silent data loss. Always consult this skill before touching any migration, even a small column add.
---

# Migrations — baseline standard

Schema is where the **entities** (`docs/06_entity-model-abstract.md`) and the **cascade laws** (`docs/04_house-rules.md`) become real. Get the cascades wrong and you violate the product's core promises (a deleted brand must not delete barbers; a leaving barber must not lose their data). This skill is **conventions + a checklist**, not code — *ask if you want the concrete Laravel patterns filled in*.

## Non-negotiables

0. **Migrations are module‑owned.** Each lives in `modules/{Name}/Database/Migrations/`, stamped `YYYY_MM_DD_000{N}_create_{table}_table.php` for ordering, with an explicit snake_case‑plural `$table` (e.g. `bookings`, `branches`, `barber_affiliations`). The module provider loads them (guarded by `File::exists`). See `module-architecture` + `docs/11`.
1. **Match the entity model.** Every table and column traces to an entity/attribute in `06`. Don't add fields the spec doesn't have; don't omit ones it does.
2. **Match the cascade laws exactly.** See the table below. This is the part most likely to be done wrong.
3. **Identifiers are UUID-style**, non-guessable (per `10`/`11` intended context).
4. **Translatable fields are bilingual JSON** holding `ar` and `en` (per `06` "translatable" + `04` J1/J2; `SpatieTranslatable` on the model). Never a single plain string for a user-facing name/description.
5. **Fixed value lists are enum-backed columns** (statuses, `gender_category`) — never free strings (pairs with the type-safety skill).
6. **Reversible + no silent data loss.** Every migration has a working down path; anything destructive routes through the **archive-file** skill first.

## Cascade & relationship rules (the critical part)

Derived directly from `docs/04_house-rules.md`. **These must hold or the product breaks its promises.**

| Relationship | Rule | Required behavior |
|---|---|---|
| Brand → Branch | A6 | Deleting a **Brand cascades** to its Branches |
| Branch → Chair | A6 | Deleting a **Branch cascades** to its Chairs |
| Brand exists → ≥1 Branch | A2 | A Brand can never have zero Branches (enforced at creation, Branch-First A1) |
| Barber ↔ Brand/Branch (affiliation) | C5 | Deleting a Brand/Branch (or ending the link) **deletes only the affiliation row** — the **Barber survives** with profile, portfolio, and services intact |
| Chair → Barber | C6, EC-10 | The Barber link is **nullable**; removing it leaves the Barber untouched (chair becomes unassigned) |
| Service owner | D1, C5 | A Service is owned by a Brand **or** a Barber and must always have an owner; a freelancer's services survive independent of any shop |
| Booking → Chair | E4, EC-17 | Chair link is **nullable** (empty for at-home bookings) |

> The trap: a naive "cascade everything from Brand" would delete barbers and their services when a shop closes. **Forbidden.** Barbers and their services are independent (Barber-Standalone invariant); only the **affiliation link** is collateral.

## Polymorphic links (the three from `06` §4)

Model all three with one consistent pattern (type + id columns):

- **Affiliation → Brand | Branch** (`affiliable`)
- **Service → Brand | Barber** (`serviceable`)
- **Settings → Brand | Branch | Barber | Client** (`settingable`)

Keep them indexed and consistent so the agent never hand-rolls a one-off variant.

## Per-migration checklist

Before marking a migration done:

- [ ] every column maps to an attribute in `docs/06`
- [ ] UUID-style primary key
- [ ] translatable fields are bilingual JSON (`ar` + `en`)
- [ ] status / category columns are enum-backed (not strings)
- [ ] foreign keys + on-delete behavior match the **cascade table** above (A6 / C5 honored)
- [ ] barbers and their services are **not** cascade-deleted by a brand/branch delete
- [ ] polymorphic columns follow the shared `…able` pattern
- [ ] a working `down()` exists
- [ ] anything destructive (drop column/table, irreversible change) was **archived first** (archive-file skill)
- [ ] the no-double-booking constraint is supported at the data layer (see concurrency-safety skill) for booking-related tables

## When this skill applies in the loop

Phase 6 **Act** (creating/altering schema). Pair with **type-safety** (enums), **concurrency-safety** (booking integrity), and **archive-file** (before destructive change). The matching tests come from the **testing** skill — every cascade rule above should have a test proving it.

> Want the actual Laravel migration code patterns for these (UUIDs, JSON columns, enum casts, `cascadeOnDelete` vs `nullOnDelete`, the polymorphic columns)? Ask and they'll be added as a `references/patterns.md` in this skill.