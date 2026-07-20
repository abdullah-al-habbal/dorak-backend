<?php

declare(strict_types=1);

namespace Modules\OfferedService\Handlers\Shared;

use Illuminate\Database\Eloquent\Collection;
use Modules\OfferedService\CQRS\Query\Shared\ListBarberServicesQuery;
use Modules\OfferedService\Eloquent\Resolvers\Shared\ListBarberServicesEloquentResolver;

final class ListBarberServicesHandler
{
    public function __construct(
        private readonly ListBarberServicesEloquentResolver $resolver,
    ) {}

    public function handle(ListBarberServicesQuery $query): Collection
    {
        return $this->resolver->resolve($query);
    }
}
