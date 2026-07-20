<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\CQRS\Command\Barber;

final readonly class AcceptAffiliationCommand
{
    public function __construct(
        public string $affiliationId,
    ) {}
}
