<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Handlers;

use Modules\ClientInteraction\CQRS\Command\AddFavoriteCommand;
use Modules\ClientInteraction\Eloquent\Resolvers\AddFavoriteEloquentResolver;
use Modules\ClientInteraction\Models\ClientFavoriteModel;

final class AddFavoriteHandler
{
    public function __construct(
        private readonly AddFavoriteEloquentResolver $resolver,
    ) {}

    public function handle(AddFavoriteCommand $command): ClientFavoriteModel
    {
        return $this->resolver->resolve($command);
    }
}
