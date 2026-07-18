<?php

declare(strict_types=1);

namespace Modules\Activation\Handlers;

use Modules\Activation\CQRS\Command\ToggleActivationCommand;
use Modules\Activation\Eloquent\Resolvers\ToggleActivationEloquentResolver;
use Modules\Activation\Models\ActivationLogModel;

final class ToggleActivationHandler
{
    public function __construct(
        private readonly ToggleActivationEloquentResolver $resolver,
    ) {}

    public function handle(ToggleActivationCommand $command): ActivationLogModel
    {
        return $this->resolver->resolve($command);
    }
}
