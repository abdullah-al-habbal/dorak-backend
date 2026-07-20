<?php

declare(strict_types=1);

namespace Modules\Booking\Handlers\Client;

use Modules\Booking\CQRS\Query\Client\ShowBookingQuery;
use Modules\Booking\Eloquent\Resolvers\Client\ShowBookingEloquentResolver;
use Modules\Booking\ValuesObjects\ShowBookingResult;

final class ShowBookingHandler
{
    public function __construct(
        private readonly ShowBookingEloquentResolver $resolver,
    ) {}

    public function handle(ShowBookingQuery $query): ShowBookingResult
    {
        return $this->resolver->resolve($query);
    }
}
