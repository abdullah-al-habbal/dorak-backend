<?php

declare(strict_types=1);

namespace Modules\Explore\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Branch\Models\BranchModel;

final class ExploreBranchesEloquentResolver
{
    public function search(float $lat, float $lng, float $radius, ?string $universe, int $perPage): LengthAwarePaginator
    {
        $haversine = sprintf(
            '(6371 * acos(cos(radians(%f)) * cos(radians(latitude)) * cos(radians(longitude) - radians(%f)) + sin(radians(%f)) * sin(radians(latitude))))',
            $lat, $lng, $lat
        );

        $subQuery = BranchModel::query()
            ->select('branches.*')
            ->selectRaw("{$haversine} AS distance")
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        if ($universe !== null) {
            $subQuery->whereHas('brand', fn ($q) => $q->where('universe', $universe));
        }

        $query = BranchModel::query()->fromSub($subQuery, 'branches_sub')
            ->where('distance', '<', $radius)
            ->orderBy('distance');

        return $query->paginate(min($perPage, 100));
    }
}
