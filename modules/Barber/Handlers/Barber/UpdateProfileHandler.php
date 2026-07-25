<?php

declare(strict_types=1);

namespace Modules\Barber\Handlers\Barber;

use Modules\Barber\CQRS\Command\Barber\UpdateProfileCommand;
use Modules\Barber\Models\BarberModel;
use Modules\Barber\Repositories\UpdateProfileEloquentResolver;

final class UpdateProfileHandler
{
    public function __construct(
        private readonly UpdateProfileEloquentResolver $resolver,
    ) {}

    public function handle(UpdateProfileCommand $command): BarberModel
    {
        return $this->resolver->resolve($command);
    }
}
