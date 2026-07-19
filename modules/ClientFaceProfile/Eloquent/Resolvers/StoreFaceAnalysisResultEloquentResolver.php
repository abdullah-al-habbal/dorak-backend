<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\Eloquent\Resolvers;

use Modules\ClientFaceProfile\CQRS\Command\StoreFaceAnalysisResultCommand;
use Modules\ClientFaceProfile\Models\ClientFaceAnalysisResultModel;

final class StoreFaceAnalysisResultEloquentResolver
{
    public function resolve(StoreFaceAnalysisResultCommand $command): ClientFaceAnalysisResultModel
    {
        return ClientFaceAnalysisResultModel::create([
            'client_id' => $command->clientId,
            'face_profile_id' => $command->faceProfileId,
            'analysis_version' => $command->analysisVersion,
            'analysis_source' => $command->analysisSource,
            'detected_face_shape' => $command->detectedFaceShape,
            'confidence_score' => $command->confidenceScore,
            'detected_features' => $command->detectedFeatures,
            'recommended_catalog_item_ids' => $command->recommendedCatalogItemIds,
            'computed_at' => now(),
        ]);
    }
}
