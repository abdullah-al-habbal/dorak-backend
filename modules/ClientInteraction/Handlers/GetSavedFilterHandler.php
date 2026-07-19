<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Handlers;

use Modules\ClientInteraction\CQRS\Query\GetSavedFilterQuery;
use Modules\ClientInteraction\Eloquent\Resolvers\GetSavedFilterEloquentResolver;
use Modules\ClientInteraction\Models\ClientSavedFilterModel;

final class GetSavedFilterHandler
{
    public function __construct(
        private readonly GetSavedFilterEloquentResolver $resolver,
    ) {}

    public function handle(GetSavedFilterQuery $query): ClientSavedFilterModel
    {
        return $this->resolver->resolve($query);
    }
}
