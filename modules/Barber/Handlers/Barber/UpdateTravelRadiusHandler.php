<?php

declare(strict_types=1);

namespace Modules\Barber\Handlers\Barber;

use Modules\Barber\CQRS\Command\Barber\UpdateTravelRadiusCommand;
use Modules\Barber\Models\BarberModel;
use Modules\Barber\Repositories\UpdateTravelRadiusEloquentResolver;

final class UpdateTravelRadiusHandler
{
    public function __construct(
        private readonly UpdateTravelRadiusEloquentResolver $resolver,
    ) {}

    public function handle(UpdateTravelRadiusCommand $command): BarberModel
    {
        return $this->resolver->resolve($command);
    }
}
