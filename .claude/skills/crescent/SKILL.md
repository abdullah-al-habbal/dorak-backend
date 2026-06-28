---
name: crescent
description: Run the 7-phase CRESCENT state machine (Idea -> Research -> Prototype -> PRD -> Kanban -> Ralph Loop -> QA) to ship a feature, bug fix, or refactor end-to-end against the project docs. Dispatches deterministically to the matching phase. Use this whenever the user wants to start CRESCENT, ship something with the full process, advance to a named phase, or says "crescent", "start the state machine", "full process this feature", "next phase", or "7 phases". Always use this skill when a unit of work needs to move from idea to verified code, even if the user doesn't say the word "crescent".
---

# CRESCENT — feature delivery state machine

CRESCENT moves one unit of work (a feature, bug fix, or refactor) from idea to verified code, **using the project docs as the single source of truth** and the **baseline Laravel skills** as the engineering standard. It is the *process layer*; see `process/PROCESS.md` for how it relates to the docs and the skills.

## Operating contract (read first, every run)

- **The docs are law.** Read from `docs/` and write back to `docs/` per the phase table below. Never invent product behavior that isn't in the docs — if new behavior is needed, add it in the **PRD** phase first.
- **Decided vs Open.** You may build autonomously only against ✅ *Decided* spec. Anything flagged 🟡 *Open* in `docs/02_prd.md` §8 (or 🟡 anywhere) **stops and asks the human**. Park such work in the **Blocked** column.
- **Deterministic dispatch.** Identify the current phase, run *only* that phase, then propose the transition. Do not skip ahead.
- **State is the Kanban + the docs.** There is no hidden memory; the board (`process/KANBAN.md`) and the docs hold all state.

## Phase dispatch

Determine where the work is and run the matching phase. If unclear, ask which phase to start.

| Phase | Goal | Reads | Writes | Skills | Exit criterion (gate) |
|---|---|---|---|---|---|
| 1 Idea | One-sentence framing + intent | `01`,`02` | idea note | — | the problem and desired outcome are stated in one paragraph |
| 2 Research | Resolve unknowns | `02`,`08`, web | research notes; **new edge cases → `08`** | — | every open unknown is answered or explicitly deferred |
| 3 Prototype | Validate the riskiest assumption | `06`,`07` | a spike (then archived) | type-safety, archive-file | the assumption is confirmed or killed; spike archived |
| 4 PRD | Freeze a feature-sized slice | `01`,`02`,`04`,`06`,`07` | feature-PRD slice; doc updates | — | scope, entities, rules, and the flow for this feature are written down |
| 5 Kanban | Decompose into small tickets | `02`,`04`,`07`,`08` | tickets in `process/KANBAN.md` | — | every ticket has doc IDs + acceptance criteria |
| 6 Ralph Loop | Build each ticket | `06`,`04`,`07` + all skills | code, migrations, tests | **all baseline** | all tickets pass their own stopping conditions |
| 7 QA | Verify against the spec | `04`,`08`,`07`, acceptance | QA report; tickets → Done | testing, validations | spec satisfied; no regressions; report written |

Recommended human gates (override with the user's choice from `process/PROCESS.md` §8): pause for sign-off **after PRD**, **after Kanban**, and **at QA**.

---

## Phase 1 — Idea
State the unit of work in one paragraph: the problem, who it's for (cite a persona from `03`), and the desired outcome. Confirm it fits the vision (`01`) and scope (`02`). Output a short idea note. **Transition →** Research.

## Phase 2 — Research
List the unknowns (libraries, approaches, local/Syria constraints, anything ambiguous in the spec). Resolve each with targeted research; web research is allowed here. **Any new scenario you discover gets written into `docs/08_edge-cases.md`** so the spec grows. Defer what can't be resolved (flag 🟡). **Transition →** Prototype (or skip to PRD if nothing needs a spike).

## Phase 3 — Prototype
Build the **smallest throwaway spike** that tests the single riskiest assumption (e.g., the backend-driven floor-plan payload renders; the concurrency guard holds under parallel requests). Apply type-safety loosely. **When done, archive the spike** via the archive-file skill — a prototype is not production code unless the user's prototype policy says otherwise (`process/PROCESS.md` §8). **Transition →** PRD.

## Phase 4 — PRD (feature slice)
Write a **feature-sized PRD slice**: goals, scope boundary, the entities it touches (from `06`), the house rules it must obey (from `04`), the user flow it implements (from `07`), and acceptance-shaped success criteria. **Update the docs** where this feature changes them (`02`/`04`/`06`/`07`). This is the contract the loop will build to. **Transition →** Kanban.

## Phase 5 — Kanban
Decompose the slice into **small tickets** (each plausibly finishable in one loop pass). Use the ticket template in `process/KANBAN.md`. Every ticket must carry: the **entity** (`06`), the **house-rule IDs** it satisfies (`04`), the **flow** (`07`), the **edge cases** to cover (`08`), the **applicable skills**, and **acceptance criteria derived from those IDs**. Put any 🟡 Open dependency in **Blocked**. **Transition →** Ralph Loop.

## Phase 6 — Ralph Loop (the engine)
For each **Ready** ticket, run the loop:

> **Think → Research → Plan → Act → Test → Review → Verify → loop/done**

- **Think:** read the ticket + its cited doc sections; load only the relevant skills.
- **Research:** only if an unknown remains (usually skip — phases 2 & 4 did it).
- **Plan:** list files to touch; name the rules/entities/flows; pick skills.
- **Act:** write code to the skills' standards (typed enums for statuses, migrations whose cascades match `04` A6/C5, Form Request validation for the If/Then rules). **Archive any file before a destructive change.**
- **Test:** write/run a test for **each cited house rule and edge case** (testing skill). Red → green.
- **Review:** self-check against every applicable skill's checklist + the doc rules; run static analysis.
- **Verify:** run the **full** suite; confirm acceptance criteria; confirm **no other house rule regressed**.
- **Loop/Done:** any failure → back to Plan/Act. All pass → ticket **Done**.

**Stopping conditions (all required):** acceptance met · every cited rule/edge-case has a passing test · static analysis clean · full suite green.
**Escalation:** after the user's chosen N failed verifications (default 3), or if the ticket touches a 🟡 Open item, **stop and ask the human**; move the ticket to **Blocked**.

**Transition →** QA when all non-blocked tickets are Done.

## Phase 7 — QA
Verify the built feature against the spec, not against itself:
- every house rule in `04` relevant to this feature has a passing test;
- every edge case in `08` relevant to this feature is covered;
- the flow in `07` works end to end;
- acceptance criteria on each ticket are met;
- no regression elsewhere.
Write a short **QA report** (what was checked, results, anything deferred), move passing tickets to **Done**, and surface remaining 🟡 items to the human. **Transition →** ship / next feature.

---

## Outputs of a full run
- Updated docs (`08` from Research; `02`/`04`/`06`/`07` from PRD).
- A populated board in `process/KANBAN.md`.
- Code + migrations + tests built to the baseline skills.
- A QA report.

## When NOT to run the whole machine
- A one-line fix with an obvious test → just do **Ralph Loop** on a single ticket.
- A pure spec question → answer from `docs/` directly; no phases.
- Anything blocked on a 🟡 Open decision → stop at **Kanban/Blocked** and ask the human.

See `process/PROCESS.md` for the full rationale and the doc-wiring principles, and `.claude/skills/laravel-baseline/README.md` for the standards the loop obeys.