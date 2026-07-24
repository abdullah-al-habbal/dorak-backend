# BE-13 — ClientFaceProfile Module (PRD Phase 3)

## Status: ✅ Complete
## Frontend Consumer: FaceProfileScreen (pending FE-04)

## What Was Built
- 2 Models: ClientFaceProfileModel (max 5 photos, one primary), ClientFaceAnalysisResultModel
- 2 ValueObjects: FaceAnalysisFeaturesValueObject, RecommendedCatalogItemIdsValueObject
- 2 Enums: DetectedFaceShapeEnum, AnalysisSourceEnum
- 2 Casts: FaceAnalysisFeaturesCast, RecommendedCatalogItemIdsCast
- UploadFaceProfilePhotoAction — stores photo, sets primary flag, dispatches AnalyzeFacePhotoJob
- GetFaceBasedRecommendationsAction — returns analysis results with recommended catalog items
- AnalyzeFacePhotoJob — async queue job for AI analysis (placeholder pipeline)

## API Endpoints
| Method | Endpoint | Auth | Action |
|--------|----------|------|--------|
| POST | `/api/v1/client/face-profile` | auth:client | `UploadFaceProfilePhotoAction` |
| GET | `/api/v1/client/face-profile/recommendations` | auth:client | `GetFaceBasedRecommendationsAction` |

## Response Schemas

### POST /api/v1/client/face-profile → 201
Multipart upload — accepts `photo` file + `is_primary` boolean.
```json
{
  "data": {
    "id": "uuid",
    "image_url": "https://...",
    "is_primary": true,
    "uploaded_at": "2026-07-24T12:00:00Z",
    "created_at": "2026-07-24T12:00:00Z"
  }
}
```

### GET /api/v1/client/face-profile/recommendations → 200
```json
{
  "data": [
    {
      "id": "uuid",
      "face_profile_id": "uuid",
      "analysis_version": "1.0",
      "analysis_source": "ai_pipeline",
      "detected_face_shape": "oval|round|square|heart|oblong",
      "confidence_score": 0.92,
      "detected_features": {
        "jawline": "defined",
        "forehead": "average",
        "cheekbones": "high"
      },
      "recommended_catalog_item_ids": ["uuid1", "uuid2"],
      "computed_at": "2026-07-24T12:05:00Z",
      "face_profile": {
        "id": "uuid",
        "image_url": "https://..."
      },
      "created_at": "2026-07-24T12:00:00Z"
    }
  ]
}
```

## Tests: 7 contract tests in ApiResponseContractTest
