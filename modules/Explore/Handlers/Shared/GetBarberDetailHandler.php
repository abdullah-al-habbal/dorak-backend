<?php

declare(strict_types=1);

namespace Modules\Explore\Handlers\Shared;

use Modules\Explore\CQRS\Query\Shared\GetBarberDetailQuery;
use Modules\Explore\Eloquent\Resolvers\Shared\GetBarberDetailEloquentResolver;

final class GetBarberDetailHandler
{
    public function __construct(
        private readonly GetBarberDetailEloquentResolver $resolver,
    ) {}

    public function handle(GetBarberDetailQuery $query): array
    {
        return $this->resolver->resolve($query);
    }
}
