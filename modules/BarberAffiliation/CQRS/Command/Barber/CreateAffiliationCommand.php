<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\CQRS\Command\Barber;

use Modules\BarberAffiliation\Enums\AffiliableType;

final readonly class CreateAffiliationCommand
{
    public function __construct(
        public string $barberId,
        public string $affiliableId,
        public AffiliableType $affiliableType,
        public ?float $commissionRate,
    ) {}
}
