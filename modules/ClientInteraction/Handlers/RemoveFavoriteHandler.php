<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Handlers;

use Modules\ClientInteraction\Eloquent\Resolvers\RemoveFavoriteEloquentResolver;

final class RemoveFavoriteHandler
{
    public function __construct(
        private readonly RemoveFavoriteEloquentResolver $resolver,
    ) {}

    public function handle(string $favoriteId, string $clientId): void
    {
        $this->resolver->resolve($favoriteId, $clientId);
    }
}
