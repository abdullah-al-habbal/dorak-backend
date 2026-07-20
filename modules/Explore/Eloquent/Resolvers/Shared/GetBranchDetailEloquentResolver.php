<?php

declare(strict_types=1);

namespace Modules\Explore\Eloquent\Resolvers\Shared;

use Modules\Barber\Http\Resources\BarberResource;
use Modules\Branch\Http\Resources\Shared\BranchResource;
use Modules\Branch\Models\BranchModel;
use Modules\Explore\CQRS\Query\Shared\GetBranchDetailQuery;
use Modules\OfferedService\Http\Resources\Shared\ServiceResource;

final class GetBranchDetailEloquentResolver
{
    public function resolve(GetBranchDetailQuery $query): array
    {
        $branch = BranchModel::with(['chairs.barber.services'])->findOrFail($query->branchId);

        $barbers = $branch->chairs
            ->pluck('barber')
            ->filter()
            ->unique('id')
            ->values();

        $services = $barbers->flatMap->services->unique('id')->values();

        return array_merge(
            (new BranchResource($branch))->toArray(request()),
            [
                'chairs_count' => $branch->chairs->count(),
                'barbers' => BarberResource::collection($barbers),
                'services' => ServiceResource::collection($services),
            ],
        );
    }
}
