<?php

declare(strict_types=1);

namespace Modules\Barber\CQRS\Command\Barber;

final readonly class UpdateScheduleCommand
{
    /**
     * @param array<array{day_of_week: int, start_time: string, end_time: string, is_active: bool}> $schedule
     */
    public function __construct(
        public string $barberId,
        public array $schedule,
    ) {}
}
