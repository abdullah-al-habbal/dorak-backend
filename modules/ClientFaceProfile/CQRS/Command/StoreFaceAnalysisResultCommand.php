<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\CQRS\Command;

use Modules\ClientFaceProfile\Enums\AnalysisSourceEnum;
use Modules\ClientFaceProfile\Enums\DetectedFaceShapeEnum;
use Modules\ClientFaceProfile\ValuesObjects\FaceAnalysisFeaturesValueObject;
use Modules\ClientFaceProfile\ValuesObjects\RecommendedCatalogItemIdsValueObject;

final readonly class StoreFaceAnalysisResultCommand
{
    public function __construct(
        public string $clientId,
        public string $faceProfileId,
        public string $analysisVersion,
        public AnalysisSourceEnum $analysisSource,
        public DetectedFaceShapeEnum $detectedFaceShape,
        public float $confidenceScore,
        public FaceAnalysisFeaturesValueObject $detectedFeatures,
        public RecommendedCatalogItemIdsValueObject $recommendedCatalogItemIds,
    ) {}
}
