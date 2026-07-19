<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\CQRS\Query;

final readonly class GetSavedFilterQuery
{
    public function __construct(
        public string $filterId,
        public string $clientId,
    ) {}
}
