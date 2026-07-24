# BE-15 — Recommendation Module (PRD Phase 5)

## Status: ✅ Complete
## Frontend Consumer: ExploreScreen (pending FE-01)

## What Was Built
- 3 Models: RecommendationEdgeModel (polymorphic edges), ClientPreferenceVectorModel (embedding storage), EntityEmbeddingModel (polymorphic entity embeddings)
- 1 Enum: EdgeTypeEnum
- ClientFavoriteObserver + BookingCompletedObserver — sync edges on events
- RecomputeClientVectorsCommand (`recommend:recompute-vectors`) — nightly batch, chunks 100, gathers signals, calls OpenAI embeddings; recomputes entity embeddings for branches and barbers
- ClientRecommendationServiceProvider — registers observers + recompute command + cron schedule

## API Endpoints
No dedicated recommendation endpoints — recommendations are embedded in the Explore API (see BE-17).

## Explore Filter Enhancements (added in Phase 5)
| Parameter | Type | Description |
|-----------|------|-------------|
| `catalog_item_ids[]` | array of int | Filter by service catalog item IDs |
| `available_now` | boolean | Only show currently available |
| `price_range[min]` | numeric | Minimum price filter |
| `price_range[max]` | numeric | Maximum price filter |
| `rating_min` | numeric (0-5) | Minimum rating filter |
| `face_shape_compatible` | string | Face shape for compatibility matching |

## Composite Ranking Formula
```
score = geo*geographic + vector_similarity*α + edge_boost*β + face_match*γ
```
Default weights: α=0.4, β=0.3, γ=0.1, geographic=0.2

Each explore result includes:
- `compatibility_score` — composite ranking score (float, nullable)
- `rank` — ordinal position after sorting (int, nullable)

## Response Schemas
See BE-17 (Explore Enhancements) for the full enhanced response shapes with `compatibility_score` and `rank` fields.

## Tests: 8 contract tests extending explore endpoint assertions + 34 dedicated feature tests across 6 modules
