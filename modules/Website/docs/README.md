# Dorak Website — Product Documentation
This folder is the **single source of truth** for the *Dorak Public Website* (Blade/Tailwind) before any code is written. It contains **no code** — only product requirements, UI/UX laws, and content architecture.

---

## Reading Order
| # | File | Purpose | Audience |
|---|---|---|---|
| — | [`README.md`](./README.md) | This index | Everyone |
| 01 | [`01VisionAndScope.md`](./01VisionAndScope.md) | Vision, problem, MVP boundary | Everyone |
| 02 | [`02Prd.md`](./02Prd.md) | **Master document.** Goals, scope, decisions | Product, Eng |
| 03 | [`03PersonaJourneys.md`](./03PersonaJourneys.md) | Visitor journeys (Owner, Client, Barber) | Product, Design |
| 04 | [`04HouseRules.md`](./04HouseRules.md) | The unshakeable UI/UX and theming laws | Everyone |
| 05 | [`05DomainGlossary.md`](./05DomainGlossary.md) | The bounded vocabulary | Everyone |
| 06 | [`06ContentAndUiModelAbstract.md`](./06ContentAndUiModelAbstract.md) | Marketing content entities (Abstract) | Eng |
| 07 | [`07UserFlows.md`](./07UserFlows.md) | Step-by-step visitor flows | Eng, Design |
| 08 | [`08EdgeCases.md`](./08EdgeCases.md) | Hard situations (SEO vs JS, missing translations) | Eng, QA |
| 09 | [`09C4Context.md`](./09C4Context.md) | C4 Level 1 — The site as one box | Eng |
| 10 | [`10C4Containers.md`](./10C4Containers.md) | C4 Level 2 — Blade, Tailwind, Alpine | Eng |
| 11 | [`11FrontendArchitecture.md`](./11FrontendArchitecture.md) | Component structure & CSS variable theming | Eng |

---

## Core Principles (The Website Invariants)
1. **Zero Discrimination Theming:** The base design is premium and neutral. The "Universe" toggle only changes accent colors and imagery, never the core layout.
2. **Backend-Driven Content:** No marketing text is hardcoded in Blade. All features, pricing, and testimonials are fetched dynamically from the Laravel API.
3. **SEO-First Rendering:** Critical marketing content must be server-side rendered in the initial HTML payload for search engines, while interactive elements (like the floor plan demo) use Alpine.js.

---

## AI Agent Instructions
AI coding agents must read these files before generating Blade or Tailwind code. Pay special attention to `04HouseRules.md` to ensure the "No Discrimination" theming law is strictly followed via CSS variables.
