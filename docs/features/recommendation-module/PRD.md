# PRD Phase 5 — Recommendation Module

## 1. Objective

Personalise explore results so clients see relevant barbers/branches first. Two-tier:
- **Tier 1 (explicit signals):** boost results by client history, favorites, face-shape compatibility
- **Tier 2 (vector similarity):** pgvector cosine-similarity against nightly-computed preference embeddings

Both tiers degrade gracefully — no vector = fall back to pure geographic proximity.

## 2. Data Model

### Table: `client_preference_vectors`

| Column | Type | Notes |
|--------|------|-------|
| `id` | `ulid` PK | |
| `client_id` | `ulid` FK → `clients.id` | Unique |
| `embedding` | `vector(1536)` | OpenAI `text-embedding-3-small` |
| `metadata` | `jsonb` | Snapshot of signals used to compute |
| `computed_at` | `timestamp` | |
| `created_at` | `timestamp` | |
| `updated_at` | `timestamp` | |

### Table: `recommendation_edges`

| Column | Type | Notes |
|--------|------|-------|
| `id` | `ulid` PK | |
| `source_type` | `string` | Morph — `client`, `client_face_profile` |
| `source_id` | `ulid` | |
| `target_type` | `string` | Morph — `branch`, `barber`, `catalog_item` |
| `target_id` | `ulid` | |
| `edge_type` | `string` | Enum: `favorite`, `visited`, `history`, `face_matched`, `similar_client` |
| `weight` | `float` | 0.0–1.0 |
| `context` | `jsonb` | Arbitrary edge metadata |
| `expires_at` | `timestamp` | Nullable TTL |
| `created_at` | `timestamp` | |

### Enum: `EdgeTypeEnum`
```php
case Favorite = 'favorite';
case Visited = 'visited';
case History = 'history';
case FaceMatched = 'face_matched';
case SimilarClient = 'similar_client';
```

### ValueObjects
- `ClientPreferenceVectorDataValueObject` — wraps vector array + metadata
- `RecommendationFactorWeightsValueObject` — per-client weighting config
- `RecommendationEdgeContextValueObject` — typed context for edge metadata

## 3. Architecture

```
[Cron: Nightly]
  RecomputeRecommendationVectorsCommand
    → Gather signals (favorites, history, face profile, demographics)
    → Generate embedding via laravel/ai (OpenAI text-embedding-3-small)
    → Upsert client_preference_vectors
    → Upsert recommendation_edges (favorite, visited, history)

[Explore endpoint — inline rerank]
  Explore{Barbers|Branches}EloquentResolver
    → Apply new filters (catalog_item_ids, available_now, price_range, rating_min, face_shape_compatible)
    → Haversine distance filter (existing)
    → Composite rerank:
        1. Cosine similarity to client preference vector (if exists)
        2. Edge-weight boost from recommendation_edges
        3. Face-shape compatibility penalty/reward
    → Order by composite score DESC
    → Paginate
```

## 4. Files to Create

### Module: `ClientRecommendation`
```
modules/ClientRecommendation/
  CQRS/Command/RecomputeClientVectorsCommand.php
  Database/Migrations/
    2026_07_19_000001_create_client_preference_vectors_table.php
    2026_07_19_000002_create_recommendation_edges_table.php
  Eloquent/Casts/EmbeddingCast.php
  Enums/EdgeTypeEnum.php
  Models/
    ClientPreferenceVectorModel.php
    RecommendationEdgeModel.php
  Observers/
    ClientFavoriteObserver.php        — Sync edges on favorite/unfavorite
    BookingCompletedObserver.php      — Sync history edges
  ValuesObjects/
    ClientPreferenceVectorDataValueObject.php
    RecommendationFactorWeightsValueObject.php
    RecommendationEdgeContextValueObject.php
  Providers/ClientRecommendationServiceProvider.php
```

### Modifications (existing files)

| File | Change |
|------|--------|
| `config/ai.php` | Possibly add `use_embeddings` flag for env |
| `modules/Explore/Http/Requests/Shared/ExploreBranchesRequest.php` | Add new filter params |
| `modules/Explore/Http/Requests/Shared/ExploreBarbersRequest.php` | Add new filter params |
| `modules/Explore/CQRS/Query/Shared/ExploreBranchesQuery.php` | Add new filter fields |
| `modules/Explore/CQRS/Query/Shared/ExploreBarbersQuery.php` | Add new filter fields |
| `modules/Explore/Eloquent/Resolvers/Shared/ExploreBranchesEloquentResolver.php` | Apply filters + composite ranking |
| `modules/Explore/Eloquent/Resolvers/Shared/ExploreBarbersEloquentResolver.php` | Apply filters + composite ranking |
| `modules/Explore/Http/Resources/Shared/BranchResource.php` | Add `compatibility_score`, `rank` |
| `modules/Explore/Http/Resources/Shared/BarberResource.php` | Add `compatibility_score`, `rank` |
| `bootstrap/providers.php` | Register `ClientRecommendationServiceProvider` |

### Console command
```
php artisan recommend:recompute-vectors {--client-id=} {--force}
```
- No args → recompute all clients (nightly batch)
- `--client-id` → recompute single client (on-demand after booking)
- `--force` → skip freshness check

Add to kernel schedule for 2 AM daily.

## 5. New Explore Filters

| Param | Type | Location | Behavior |
|-------|------|----------|----------|
| `catalog_item_ids[]` | `ulid[]` | Request+Query | Filter to branches/barbers offering at least one of these catalog items |
| `available_now` | `boolean` | Request+Query | Filter to barbers/branches with available chairs in current time slot |
| `price_range[min]` | `float` | Request+Query | Min offered service price |
| `price_range[max]` | `float` | Request+Query | Max offered service price |
| `rating_min` | `float` | Request+Query | Min average rating (from reviews if exists, else omit) |
| `face_shape_compatible` | `string` | Request+Query | Filter to catalog items matching the client's face shape analysis |

Universe param stays for branches. Barbers endpoint stays universe-less.

## 6. Ranking Formula

```
composite_score = (1 - α - β - γ) * geographic_score
                + α * preference_vector_similarity
                + β * edge_weight_boost
                + γ * face_shape_compatibility

Default weights: α=0.4, β=0.3, γ=0.1 (geographic remains 0.2)

Where:
  geographic_score           = 1 / (1 + distance_km / radius)
  preference_vector_similarity = cosine(client.embedding, target.embedding)
  edge_weight_boost          = max(edge.weight) for client→target edges
  face_shape_compatibility   = 1.0 if compatible, 0.0 otherwise
```

No vector available → α=0, remaining weight redistributed proportionally.

## 7. Nightly Batch Cron

```
Schedule:
  0 2 * * * php artisan recommend:recompute-vectors
  (appended to existing FeaturesScheduleProvider via ->dailyAt('02:00'))
```

Steps:
1. Query all clients with at least one signal (favorite, history, face analysis)
2. For each client, collect signal text (e.g. "visited barber X known for fade haircut, favorited branch specializing in beard grooming, face shape oval")
3. Generate embedding via `Laravel\AI\Embeddings\EmbeddingDriver::embedText($text)`
4. Upsert `client_preference_vectors` for that client
5. Rebuild `recommendation_edges` for `favorite` and `history` edge types
6. Delete expired edges and stale orphan vectors

Batch size: 100 clients per chunk to avoid OOM.

## 8. Migration Steps

1. `CREATE EXTENSION vector;` — run manually or via `php artisan pgsql:create-extension vector`
2. `php artisan make:migration` for both tables
3. Index: `CREATE INDEX idx_preference_vectors ON client_preference_vectors USING ivfflat (embedding vector_cosine_ops);` for approximate nearest-neighbor queries

## 9. Test Plan

| Test | Type | What |
|------|------|------|
| RecommendEdgeTypeEnumTest | Unit | Each case has correct value |
| PreferenceVectorModelTest | Unit | CRUD, upsert, vector column |
| RecommendationEdgeModelTest | Unit | CRUD, morph relations, expiry |
| ExploreBranchesRequestTest | Unit | Validates all new filters |
| ExploreBarbersRequestTest | Unit | Validates all new filters |
| ExploreBranchesApiResponseContractTest | Existing | Extend for new response fields |
| ExploreBarbersApiResponseContractTest | Existing | Extend for new response fields |
| RecomputeClientVectorsCommandTest | Feature | Dry-run mode, verifies SQL |
| CompositeRankingTest | Feature | Ensure boosted results appear first |
| EmptyClientVectorFallbackTest | Feature | No vector → same results as today |

## 10. Risks

| Risk | Mitigation |
|------|-----------|
| `vector` extension not available on hosting | Use `float[]` + in-app cosine as fallback; check PG version (≥14 required) |
| OpenAI embedding API latency | Cache embeddings, batch nightly, degrade to geographic-only during API failures |
| Ranking formula feels arbitrary | Make weights configurable via `RecommendationFactorWeightsValueObject` + admin panel later |
| Explore response shape changes break mobile | Add new fields at end of response; `compatibility_score` is nullable + defaults null |
| No reviews/ratings data yet | `rating_min` filter gracefully ignored when no ratings table exists; no crash |

## 11. User Stories

| # | Story | Acceptance Criteria | Priority |
|---|-------|-------------------|----------|
| US-01 | As a client, I want explore results to show relevant branches/barbers first based on my past favorites and visits | Explore results reordered by composite score when client is authenticated; falls back to geographic when no signals exist | P0 |
| US-02 | As a client, I want to filter explore by specific catalog items (services) | `catalog_item_ids[]` param filters to branches/barbers offering at least one matching service | P0 |
| US-03 | As a client, I want to filter explore by price range | `price_range[min/max]` params filter by offered service price | P1 |
| US-04 | As a client, I want to filter explore by minimum rating | `rating_min` param filters results; gracefully ignored when no ratings data | P1 |
| US-05 | As a client, I want to filter explore by barbers available now | `available_now` param filters to barbers with free chairs in current time slot | P1 |
| US-06 | As a client, I want explore results boosted for services matching my face shape | `face_shape_compatible` param + ranking boost from face analysis data | P2 |
| US-07 | As a developer, I want a nightly command to recompute client preference vectors | `recommend:recompute-vectors` command runs daily at 2 AM, processes clients in chunks | P0 |
| US-08 | As a developer, I want recommendation edges synced in real-time on key events | Observers on favorite/unfavorite and booking completion create/update recommendation_edges | P0 |
