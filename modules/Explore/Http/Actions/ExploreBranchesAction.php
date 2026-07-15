<?php

declare(strict_types=1);

namespace Modules\Explore\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Branch\Http\Resources\BranchResource;
use Modules\Branch\Models\BranchModel;
use Modules\Core\Http\Actions\BaseApiAction;

final class ExploreBranchesAction extends BaseApiAction
{
    public function __invoke(): JsonResponse
    {
        $lat = (float) request()->query('lat', 0);
        $lng = (float) request()->query('lng', 0);
        $radius = (float) request()->query('radius', 10);
        $universe = request()->query('universe');

        $haversine = sprintf(
            '(6371 * acos(cos(radians(%f)) * cos(radians(latitude)) * cos(radians(longitude) - radians(%f)) + sin(radians(%f)) * sin(radians(latitude))))',
            $lat, $lng, $lat
        );

        $query = BranchModel::query()
            ->select('branches.*')
            ->selectRaw("{$haversine} AS distance")
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->havingRaw('distance < ?', [$radius])
            ->orderBy('distance');

        if ($universe !== null && in_array($universe, ['men', 'women', 'neutral'], true)) {
            $query->whereHas('brand', fn ($q) => $q->where('universe', $universe));
        }

        $perPage = (int) request()->query('per_page', 20);
        $branches = $query->paginate(min($perPage, 100));

        return $this->paginated(
            paginator: $branches,
            resourceClass: BranchResource::class,
        );
    }
}
