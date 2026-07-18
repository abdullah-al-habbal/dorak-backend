<?php

declare(strict_types=1);

namespace Modules\Explore\Handlers;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Explore\Repositories\ExploreBranchesEloquentResolver;

final class ExploreBranchesHandler
{
    public function __construct(
        private readonly ExploreBranchesEloquentResolver $resolver,
    ) {}

    public function handle(float $lat, float $lng, float $radius, ?string $universe, int $perPage): LengthAwarePaginator
    {
        return $this->resolver->search($lat, $lng, $radius, $universe, $perPage);
    }
}
