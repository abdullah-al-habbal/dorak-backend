<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\Services;

use InvalidArgumentException;
use Modules\ClientFaceProfile\Enums\AnalysisSourceEnum;
use Modules\ClientFaceProfile\Enums\DetectedFaceShapeEnum;
use Modules\ClientFaceProfile\ValuesObjects\FaceAnalysisFeaturesValueObject;
use Modules\ClientFaceProfile\ValuesObjects\FaceAnalysisOutcomeValueObject;

final class FaceAnalysisResponseParser
{
    public function parse(string $rawResponse): FaceAnalysisOutcomeValueObject
    {
        $data = json_decode($rawResponse, true, 512, JSON_THROW_ON_ERROR);
        $data = $data['data'] ?? $data;

        $shape = DetectedFaceShapeEnum::tryFrom((string) ($data['face_shape'] ?? ''));
        if ($shape === null) {
            throw new InvalidArgumentException(
                'Unsupported face shape returned by analyzer: '.(string) ($data['face_shape'] ?? 'null')
            );
        }

        $confidence = min(1.0, max(0.0, (float) ($data['confidence'] ?? 0.0)));

        return new FaceAnalysisOutcomeValueObject(
            analysisVersion: 'openai-vision-v1',
            analysisSource: AnalysisSourceEnum::ThirdPartyApi,
            detectedFaceShape: $shape,
            confidenceScore: $confidence,
            detectedFeatures: FaceAnalysisFeaturesValueObject::fromArray($data['features'] ?? []),
        );
    }
}
