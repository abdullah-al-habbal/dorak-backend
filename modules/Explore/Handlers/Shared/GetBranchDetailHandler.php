<?php

declare(strict_types=1);

namespace Modules\Explore\Handlers\Shared;

use Modules\Explore\CQRS\Query\Shared\GetBranchDetailQuery;
use Modules\Explore\Eloquent\Resolvers\Shared\GetBranchDetailEloquentResolver;

final class GetBranchDetailHandler
{
    public function __construct(
        private readonly GetBranchDetailEloquentResolver $resolver,
    ) {}

    public function handle(GetBranchDetailQuery $query): array
    {
        return $this->resolver->resolve($query);
    }
}
