<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Handlers;

use Modules\ClientInteraction\CQRS\Command\UpdateSavedFilterCommand;
use Modules\ClientInteraction\Eloquent\Resolvers\UpdateSavedFilterEloquentResolver;
use Modules\ClientInteraction\Models\ClientSavedFilterModel;

final class UpdateSavedFilterHandler
{
    public function __construct(
        private readonly UpdateSavedFilterEloquentResolver $resolver,
    ) {}

    public function handle(UpdateSavedFilterCommand $command): ClientSavedFilterModel
    {
        return $this->resolver->resolve($command);
    }
}
