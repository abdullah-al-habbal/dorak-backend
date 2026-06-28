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