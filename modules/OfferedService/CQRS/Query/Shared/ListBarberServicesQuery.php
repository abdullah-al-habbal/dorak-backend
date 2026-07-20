<?php

declare(strict_types=1);

namespace Modules\OfferedService\CQRS\Query\Shared;

final readonly class ListBarberServicesQuery
{
    public function __construct(
        public string $barberId,
    ) {}
}
