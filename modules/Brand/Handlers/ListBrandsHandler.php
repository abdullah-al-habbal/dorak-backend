<?php

declare(strict_types=1);

namespace Modules\Brand\Handlers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Brand\CQRS\Query\ListBrandsQuery;
use Modules\Brand\Eloquent\Resolvers\ListBrandsEloquentResolver;

final class ListBrandsHandler
{
    public function __construct(
        private readonly ListBrandsEloquentResolver $resolver,
    ) {}

    public function handle(ListBrandsQuery $payload): LengthAwarePaginator
    {
        return $this->resolver->resolve($payload);
    }
}
