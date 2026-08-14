<?php

declare(strict_types=1);

namespace Modules\Booking\Handlers\Client;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Booking\CQRS\Query\Client\ListUserBookingsQuery;
use Modules\Booking\Eloquent\Resolvers\Client\ListUserBookingsEloquentResolver;

final class ListUserBookingsHandler
{
    public function __construct(
        private readonly ListUserBookingsEloquentResolver $resolver,
    ) {}

    public function handle(ListUserBookingsQuery $query): LengthAwarePaginator
    {
        return $this->resolver->resolve($query);
    }
}
