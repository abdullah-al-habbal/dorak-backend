<?php

declare(strict_types=1);

namespace Modules\ClientHistory\CQRS\Query;

final readonly class ListClientServiceHistoryQuery
{
    public function __construct(
        public string $clientId,
        public int $perPage,
        public ?string $catalogItemId,
    ) {}
}
