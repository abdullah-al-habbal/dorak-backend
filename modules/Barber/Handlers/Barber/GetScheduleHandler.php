<?php

declare(strict_types=1);

namespace Modules\Barber\Handlers\Barber;

use Illuminate\Database\Eloquent\Collection;
use Modules\Barber\CQRS\Query\Barber\GetScheduleQuery;
use Modules\Barber\Repositories\GetScheduleEloquentResolver;

final class GetScheduleHandler
{
    public function __construct(
        private readonly GetScheduleEloquentResolver $resolver,
    ) {}

    public function handle(GetScheduleQuery $query): Collection
    {
        return $this->resolver->resolve($query);
    }
}
