<?php

declare(strict_types=1);

namespace Modules\Explore\Handlers;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Explore\Repositories\ExploreBarbersEloquentResolver;

final class ExploreBarbersHandler
{
    public function __construct(
        private readonly ExploreBarbersEloquentResolver $resolver,
    ) {}

    public function handle(float $lat, float $lng, float $radius, int $perPage): LengthAwarePaginator
    {
        return $this->resolver->search($lat, $lng, $radius, $perPage);
    }
}
