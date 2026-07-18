<?php

declare(strict_types=1);

namespace Modules\Activation\Handlers\Business;

use Modules\Activation\CQRS\Command\Business\ToggleActivationCommand;
use Modules\Activation\Eloquent\Resolvers\Business\ToggleActivationEloquentResolver;
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
