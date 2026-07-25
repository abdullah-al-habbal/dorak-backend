<?php

declare(strict_types=1);

namespace Modules\Barber\CQRS\Command\Barber;

final readonly class UpdateTravelRadiusCommand
{
    public function __construct(
        public string $barberId,
        public ?float $travelRadius,
        public ?float $latitude,
        public ?float $longitude,
    ) {}
}
