<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\CQRS\Command;

final readonly class CreateAffiliationCommand
{
    public function __construct(
        public string $barberId,
        public string $affiliableId,
        public string $affiliableType,
        public ?float $commissionRate,
    ) {}
}
