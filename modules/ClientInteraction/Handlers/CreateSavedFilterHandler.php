<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Handlers;

use Modules\ClientInteraction\CQRS\Command\CreateSavedFilterCommand;
use Modules\ClientInteraction\Eloquent\Resolvers\CreateSavedFilterEloquentResolver;
use Modules\ClientInteraction\Models\ClientSavedFilterModel;

final class CreateSavedFilterHandler
{
    public function __construct(
        private readonly CreateSavedFilterEloquentResolver $resolver,
    ) {}

    public function handle(CreateSavedFilterCommand $command): ClientSavedFilterModel
    {
        return $this->resolver->resolve($command);
    }
}
