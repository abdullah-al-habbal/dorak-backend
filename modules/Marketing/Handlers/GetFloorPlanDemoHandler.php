<?php
declare(strict_types=1);

namespace Modules\Marketing\Handlers;

use Modules\Marketing\Eloquent\Resolvers\FloorPlanDemoEloquentResolver;

final class GetFloorPlanDemoHandler
{
    public function __construct(
        private readonly FloorPlanDemoEloquentResolver $resolver,
    ) {}

    public function handle(): ?array
    {
        return $this->resolver->getDemoFloorPlan();
    }
}
