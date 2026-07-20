<?php

declare(strict_types=1);

namespace Modules\Ban\CQRS\Query\Client;

final readonly class CheckClientBanQuery
{
    public function __construct(
        public string $clientId,
    ) {}
}
