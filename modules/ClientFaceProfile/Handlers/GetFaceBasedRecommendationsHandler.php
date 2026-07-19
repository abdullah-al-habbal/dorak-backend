<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\Handlers;

use Illuminate\Database\Eloquent\Collection;
use Modules\ClientFaceProfile\CQRS\Query\GetFaceBasedRecommendationsQuery;
use Modules\ClientFaceProfile\Eloquent\Resolvers\GetFaceBasedRecommendationsEloquentResolver;

final class GetFaceBasedRecommendationsHandler
{
    public function __construct(
        private readonly GetFaceBasedRecommendationsEloquentResolver $resolver,
    ) {}

    public function handle(GetFaceBasedRecommendationsQuery $query): Collection
    {
        return $this->resolver->resolve($query);
    }
}
