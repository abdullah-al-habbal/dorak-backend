<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\CQRS\Query;

final readonly class GetDiscoveryPreferenceQuery
{
    public function __construct(
        public string $clientId,
    ) {}
}
