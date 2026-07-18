<?php

declare(strict_types=1);

namespace Modules\Explore\Eloquent\Resolvers\Shared;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Branch\Models\BranchModel;
use Modules\Explore\CQRS\Query\Shared\ExploreBranchesQuery;

final class ExploreBranchesEloquentResolver
{
    public function resolve(ExploreBranchesQuery $payload): LengthAwarePaginator
    {
        $haversine = sprintf(
            '(6371 * acos(cos(radians(%f)) * cos(radians(latitude)) * cos(radians(longitude) - radians(%f)) + sin(radians(%f)) * sin(radians(latitude))))',
            $payload->lat, $payload->lng, $payload->lat
        );

        $subQuery = BranchModel::query()
            ->select('branches.*')
            ->selectRaw("{$haversine} AS distance")
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        if ($payload->universe !== null) {
            $subQuery->whereHas('brand', fn ($q) => $q->where('universe', $payload->universe));
        }

        $query = BranchModel::query()->fromSub($subQuery, 'branches_sub')
            ->where('distance', '<', $payload->radius)
            ->orderBy('distance');

        return $query->paginate($payload->perPage);
    }
}
