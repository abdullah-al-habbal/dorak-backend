<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Handlers;

use Modules\ClientInteraction\Eloquent\Resolvers\DeleteSavedFilterEloquentResolver;

final class DeleteSavedFilterHandler
{
    public function __construct(
        private readonly DeleteSavedFilterEloquentResolver $resolver,
    ) {}

    public function handle(string $filterId, string $clientId): void
    {
        $this->resolver->resolve($filterId, $clientId);
    }
}
