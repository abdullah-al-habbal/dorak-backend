<?php

declare(strict_types=1);

namespace Modules\Barber\Handlers\Barber;

use Modules\Barber\CQRS\Command\Barber\UpdateScheduleCommand;
use Modules\Barber\Repositories\UpdateScheduleEloquentResolver;

final class UpdateScheduleHandler
{
    public function __construct(
        private readonly UpdateScheduleEloquentResolver $resolver,
    ) {}

    public function handle(UpdateScheduleCommand $command): array
    {
        return $this->resolver->resolve($command);
    }
}
