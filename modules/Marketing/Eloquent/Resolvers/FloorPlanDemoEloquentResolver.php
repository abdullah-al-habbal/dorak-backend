<?php

declare(strict_types=1);

namespace Modules\Marketing\Eloquent\Resolvers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Branch\Models\BranchModel;
use Modules\Chair\Models\ChairModel;
use Modules\Core\Eloquent\Resolvers\BaseEloquentResolver;

final class FloorPlanDemoEloquentResolver extends BaseEloquentResolver
{
    private const DEMO_BRANCH_EMAIL = 'demo@dorak.sy';

    public function getDemoFloorPlan(): ?array
    {
        $branch = BranchModel::query()->where('email', self::DEMO_BRANCH_EMAIL)->first();

        if ($branch === null) {
            return null;
        }

        $chairs = ChairModel::query()
            ->where('branch_id', $branch->id)
            ->orderBy('label')
            ->get();

        return [
            'branch' => [
                'id' => $branch->id,
                'name' => $branch->name,
            ],
            'canvas' => [
                'width' => 800,
                'height' => 600,
            ],
            'chairs' => $chairs->map(fn (ChairModel $chair): array => [
                'id' => $chair->id,
                'label' => $chair->label,
                'status' => $chair->status,
                'ui_metadata' => $chair->ui_metadata,
            ])->toArray(),
        ];
    }

    public function resolve(object $payload): Model|Collection|array|null
    {
        return $this->getDemoFloorPlan();
    }
}
