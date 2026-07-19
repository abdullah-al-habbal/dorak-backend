<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Modules\ClientFaceProfile\CQRS\Command\StoreFaceAnalysisResultCommand;
use Modules\ClientFaceProfile\Enums\AnalysisSourceEnum;
use Modules\ClientFaceProfile\Enums\DetectedFaceShapeEnum;
use Modules\ClientFaceProfile\Handlers\StoreFaceAnalysisResultHandler;
use Modules\ClientFaceProfile\ValuesObjects\FaceAnalysisFeaturesValueObject;
use Modules\ClientFaceProfile\ValuesObjects\RecommendedCatalogItemIdsValueObject;

class AnalyzeFacePhotoJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(
        private readonly string $faceProfileId,
        private readonly string $clientId,
    ) {}

    public function handle(StoreFaceAnalysisResultHandler $handler): void
    {
        // MVP stub: no real AI integration yet.
        // Replace with actual 3rd-party API or internal Python service call (OD-1).
        $command = new StoreFaceAnalysisResultCommand(
            clientId: $this->clientId,
            faceProfileId: $this->faceProfileId,
            analysisVersion: 'mvp-stub-v1',
            analysisSource: AnalysisSourceEnum::InternalPythonService,
            detectedFaceShape: DetectedFaceShapeEnum::Oval,
            confidenceScore: 0.75,
            detectedFeatures: FaceAnalysisFeaturesValueObject::fromArray([
                'forehead_width' => 110,
                'jaw_angle' => 75,
                'cheekbone_prominence' => 'medium',
            ]),
            recommendedCatalogItemIds: RecommendedCatalogItemIdsValueObject::fromArray([
                'ids' => [],
            ]),
        );

        $handler->handle($command);
    }
}
