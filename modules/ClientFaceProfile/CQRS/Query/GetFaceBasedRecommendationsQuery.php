<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\CQRS\Query;

final readonly class GetFaceBasedRecommendationsQuery
{
    public function __construct(
        public string $clientId,
    ) {}
}
