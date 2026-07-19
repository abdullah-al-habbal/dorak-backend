<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\CQRS\Command;

final readonly class RequestFaceAnalysisCommand
{
    public function __construct(
        public string $faceProfileId,
        public string $clientId,
    ) {}
}
