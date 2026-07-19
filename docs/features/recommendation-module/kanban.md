# Kanban — Recommendation Module

**Feature:** recommendation-module
**PRD:** PRD.md
**Created:** 2026-07-19

## Status Legend

- 🟡 Backlog
- 🔵 In Progress
- 🟢 Done
- 🔴 Blocked

---

### I-1: `recommendation_edges` table + `EdgeTypeEnum` + morph relations 🟢

**Priority:** P0 · **Blocked by:** none · **User stories:** US-08

---

### I-2: Observers — sync edges on favorite/unfavorite + booking complete 🟢

**Priority:** P0 · **Blocked by:** I-1 · **User stories:** US-08

---

### I-3: `client_preference_vectors` table + `EmbeddingCast` + model 🟢

**Priority:** P0 · **Blocked by:** none · **User stories:** US-07

---

### I-4: `RecomputeRecommendationVectorsCommand` + cron schedule 🟢

**Priority:** P0 · **Blocked by:** I-3 · **User stories:** US-07

---

### I-5: New Explore filters — request + query + resolver plumbing 🟢

**Priority:** P0 · **Blocked by:** none · **User stories:** US-02, US-03, US-04, US-05, US-06

---

### I-6: Composite ranking in Explore resolvers 🟢

**Priority:** P0 · **Blocked by:** I-1, I-3, I-5 · **User stories:** US-01

---

### I-7: Response fields — `compatibility_score`, `rank` 🟢

**Priority:** P1 · **Blocked by:** I-6 · **User stories:** US-01

---

### I-8: Contract tests for new filters + ranking + response shape 🟢

**Priority:** P0 · **Blocked by:** I-6 · **User stories:** All
