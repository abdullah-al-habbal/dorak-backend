<?php

declare(strict_types=1);

namespace Modules\Branch\Handlers\Shared;

use Modules\Branch\CQRS\Query\Shared\GetFloorPlanQuery;
use Modules\Branch\Eloquent\Resolvers\Shared\GetFloorPlanEloquentResolver;
use Modules\Branch\Models\BranchModel;

final class GetFloorPlanHandler
{
    public function __construct(
        private readonly GetFloorPlanEloquentResolver $resolver,
    ) {}

    public function handle(GetFloorPlanQuery $query): BranchModel
    {
        return $this->resolver->resolve($query);
    }
}
