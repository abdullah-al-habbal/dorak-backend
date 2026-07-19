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

### I-1: `recommendation_edges` table + `EdgeTypeEnum` + morph relations 🟡

**Priority:** P0 · **Blocked by:** none · **User stories:** US-08

**Tasks:**
- [ ] Create `EdgeTypeEnum` in `ClientRecommendation/Enums`
- [ ] Create `RecommendationEdgeContextValueObject`
- [ ] Create migration for `recommendation_edges` table (morph columns, edge type enum, weight, context JSON, expires_at)
- [ ] Create `RecommendationEdgeModel`
- [ ] Create `ClientRecommendationServiceProvider`
- [ ] Register provider in `bootstrap/providers.php`

---

### I-2: Observers — sync edges on favorite/unfavorite + booking complete 🟡

**Priority:** P0 · **Blocked by:** I-1 · **User stories:** US-08

**Tasks:**
- [ ] Create `ClientFavoriteObserver` — listen to favorite/unfavorite events, create/delete recommendation edges
- [ ] Create `BookingCompletedObserver` in ClientRecommendation module — listen to booking completion, create history edge
- [ ] Register observers on models

---

### I-3: `client_preference_vectors` table + `EmbeddingCast` + model 🟡

**Priority:** P0 · **Blocked by:** none · **User stories:** US-07

**Tasks:**
- [ ] Create `ClientPreferenceVectorDataValueObject`
- [ ] Create `RecommendationFactorWeightsValueObject`
- [ ] Create `EmbeddingCast` (casts float[] to vector-compatible format)
- [ ] Create migration for `client_preference_vectors` table (client_id unique, embedding vector(1536), metadata JSON, computed_at)
- [ ] Create `ClientPreferenceVectorModel`
- [ ] Add ivfflat index migration

---

### I-4: `RecomputeRecommendationVectorsCommand` + cron schedule 🟡

**Priority:** P0 · **Blocked by:** I-3 · **User stories:** US-07

**Tasks:**
- [ ] Create `RecomputeClientVectorsCommand` — gather signals, generate embedding via laravel/ai, upsert vectors
- [ ] Support `--client-id` for single recompute
- [ ] Support `--force` to skip freshness check
- [ ] Register cron schedule (daily at 02:00)
- [ ] Batch processing (100 clients per chunk)

---

### I-5: New Explore filters — request + query + resolver plumbing 🟡

**Priority:** P0 · **Blocked by:** none · **User stories:** US-02, US-03, US-04, US-05, US-06

**Tasks:**
- [ ] Add `catalog_item_ids[]` to `ExploreBranchesRequest`, `ExploreBarbersRequest`, and both Query objects
- [ ] Add `available_now` to both Requests and Queries
- [ ] Add `price_range[min/max]` to both Requests and Queries
- [ ] Add `rating_min` to both Requests and Queries
- [ ] Add `face_shape_compatible` to both Requests and Queries
- [ ] Implement filter logic in `ExploreBranchesEloquentResolver`
- [ ] Implement filter logic in `ExploreBarbersEloquentResolver`

---

### I-6: Composite ranking in Explore resolvers 🟡

**Priority:** P0 · **Blocked by:** I-1, I-3, I-5 · **User stories:** US-01

**Tasks:**
- [ ] Query client preference vector (if authenticated client)
- [ ] Query recommendation edges for boosts
- [ ] Query face shape compatibility
- [ ] Implement composite scoring formula: `(1-α-β-γ)·geo + α·vector_sim + β·edge_boost + γ·face_match`
- [ ] Fallback to pure geographic when no vector available
- [ ] Order results by composite score DESC
- [ ] Support configurable weights

---

### I-7: Response fields — `compatibility_score`, `rank` 🟡

**Priority:** P1 · **Blocked by:** I-6 · **User stories:** US-01

**Tasks:**
- [ ] Add `compatibility_score` nullable float to `BranchResource`
- [ ] Add `compatibility_score` nullable float to `BarberResource`
- [ ] Add `rank` integer to both resources
- [ ] Return null when client is unauthenticated

---

### I-8: Contract tests for new filters + ranking + response shape 🟡

**Priority:** P0 · **Blocked by:** I-6 · **User stories:** All

**Tasks:**
- [ ] Extend `ExploreBranchesApiResponseContractTest` for new filter params
- [ ] Extend `ExploreBarbersApiResponseContractTest` for new filter params
- [ ] Test composite ranking produces correct order
- [ ] Test empty-client-vector fallback returns same results as today
- [ ] Test `RecomputeRecommendationVectorsCommand` in dry-run mode
- [ ] Test `recommendation_edges` are created/removed on favorite toggle
