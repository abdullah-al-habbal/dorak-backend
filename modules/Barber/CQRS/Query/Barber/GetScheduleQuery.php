<?php

declare(strict_types=1);

namespace Modules\Barber\CQRS\Query\Barber;

final readonly class GetScheduleQuery
{
    public function __construct(
        public string $barberId,
    ) {}
}
