<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\Handlers;

use Modules\ClientFaceProfile\CQRS\Command\RequestFaceAnalysisCommand;
use Modules\ClientFaceProfile\Jobs\AnalyzeFacePhotoJob;

final class RequestFaceAnalysisHandler
{
    public function handle(RequestFaceAnalysisCommand $command): void
    {
        AnalyzeFacePhotoJob::dispatch(
            faceProfileId: $command->faceProfileId,
            clientId: $command->clientId,
        );
    }
}
