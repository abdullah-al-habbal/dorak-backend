---
name: laravel-testing-baseline
description: Project standard for testing the salon/barbershop platform. Use whenever writing or reviewing tests, defining a ticket's Definition of Done, or running QA. Enforces the core rule that EVERY house rule in docs/04 becomes at least one test, EVERY edge case in docs/08 becomes a test, and EVERY user flow in docs/07 becomes a feature test - including the flagship no-double-booking concurrency test (rule E1 / EC-1) and the tenancy-isolation test (rule H7). Always consult this skill before marking any ticket Done; "done" is defined by these tests, not by vibes.
---

# Testing — baseline standard

Tests are how the **specification becomes executable**. This skill turns the docs into the suite that defines "done". It is the testing *policy* and a doc-to-test map — *ask if you want the concrete Pest/PHPUnit code patterns filled in*.

**Fixed stack (from `docs/11` §8):** **Pest + PHPUnit 11**, **SQLite `:memory:`** (no external DB), tests in `tests/{Feature,Unit,Integration}/`, **factories in each `modules/{Name}/Database/Factories/`**, run with `composer test`. Tests must be deterministic and make no real external calls.

## The core rule

> **Every house rule (`docs/04`) → at least one test. Every edge case (`docs/08`) → a test. Every user flow (`docs/07`) → a feature test.**

A ticket is **not Done** until every house rule and edge case it cites (`process/KANBAN.md`) has a passing test. QA (CRESCENT phase 7) is just confirming these exist and pass.

## What to test, by source doc

- **House rules (`04`)** → unit/feature tests, one law at a time. The If/Then phrasing *is* the test: "If X then Y" → arrange X, assert Y.
- **Edge cases (`08`)** → targeted tests for the hard path (concurrency, downgrade, termination, missing translation, etc.).
- **Flows (`07`)** → end-to-end feature tests for the happy path of each flow.
- **Entities (`06`)** → factories for every entity so the above are easy to set up.

## The flagship tests (do not skip)

These prove the product's promises. Treat them as must-haves.

| Test | Proves | Source |
|---|---|---|
| **No-double-booking (concurrency)** | two simultaneous bookings on the same chair+slot → **exactly one** succeeds; the other gets a clear "seat taken" | E1, E2 / EC-1 / NFR `02` §9 |
| **Maintenance chair unbookable** | a chair in `maintenance` cannot be booked | B3 / EC-2 |
| **Branch-First on create** | creating a Brand always yields exactly one Branch | A1, A2 / Flow 4 |
| **Multi-branch gate** | a 2nd Branch is rejected unless the `multi_branch` flag is on | A3, I2 / Flow 5 |
| **Brand delete keeps barbers** | deleting a Brand removes branches/chairs but **not** barbers or their services; only the affiliation ends | A6, C5 / EC-7, EC-10 |
| **Tenancy isolation** | a user of Brand A can never read or mutate Brand B's data | H7 / NFR `02` §9 |
| **Bilingual fallback** | a field with only `ar` (or only `en`) never renders empty | J2 / EC-16 |
| **Currency on the fly** | converted price comes from the rate layer; dual display shows both when enabled | D4, D5 / Flow 10 |

> The **concurrency test is the single most important test in the codebase.** It must actually exercise parallel/contending requests, not just call the endpoint twice in sequence. Without it, BK-01 (and any booking ticket) cannot be marked Done.

## Doc-to-test mapping (worked sample)

Showing the pattern for a few rules; apply the same translation to all cited rules per ticket.

| Rule (04) | Test asserts |
|---|---|
| **E1** | concurrent attempts on same chair+slot → one success, one "seat taken" error |
| **E2** | a successful booking locks that exact chair+slot (a second attempt fails) |
| **B3** | booking a `maintenance` chair is rejected |
| **A6** | deleting a Brand deletes its branches + chairs |
| **C5** | deleting that Brand leaves affiliated barbers + their services intact |
| **G4** | an invited barber can accept or reject; only accept activates the affiliation |
| **F2** | a review cannot be created without a completed booking |

## Per-ticket checklist

- [ ] a test exists for **each** house rule the ticket cites
- [ ] a test exists for **each** edge case the ticket cites
- [ ] a feature test exists for the cited flow's happy path
- [ ] factories exist for every entity the ticket touches
- [ ] the relevant flagship test(s) above are present and passing
- [ ] full suite is green (no regression in unrelated areas)
- [ ] tests are deterministic (no order dependence, no real external calls)

## Boundaries

- **Don't** write tests for 🟡 *Open* behavior (`02` §8) — that work is Blocked until the human decides. Test only the ✅ *Decided* half (mirrors how tickets split at the 🟡 boundary, see `process/KANBAN.md` BK-01).
- **Don't** assert implementation details the docs don't fix (e.g., whether the floor plan is stored vs computed — `06`/`10` leave that open).

## When this skill applies in the loop

Phase 6 **Test / Review / Verify**, and all of phase 7 **QA**. It defines the stopping conditions the loop checks before marking a ticket Done.

> Want the concrete Pest/PHPUnit patterns (the parallel-request concurrency harness, factory setups, the tenancy-isolation helper)? Ask and they'll be added as `references/patterns.md` in this skill.