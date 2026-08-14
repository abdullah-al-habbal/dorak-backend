<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\ValuesObjects;

use Modules\ClientFaceProfile\Enums\AnalysisSourceEnum;
use Modules\ClientFaceProfile\Enums\DetectedFaceShapeEnum;

final readonly class FaceAnalysisOutcomeValueObject
{
    public function __construct(
        public string $analysisVersion,
        public AnalysisSourceEnum $analysisSource,
        public DetectedFaceShapeEnum $detectedFaceShape,
        public float $confidenceScore,
        public FaceAnalysisFeaturesValueObject $detectedFeatures,
    ) {}
}
