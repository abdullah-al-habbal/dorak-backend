<?php

declare(strict_types=1);

namespace Modules\Explore\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Barber\Http\Resources\BarberResource;
use Modules\Barber\Models\BarberModel;
use Modules\Core\Http\Actions\BaseApiAction;

final class ExploreBarbersAction extends BaseApiAction
{
    public function __invoke(): JsonResponse
    {
        $lat = (float) request()->query('lat', 0);
        $lng = (float) request()->query('lng', 0);
        $radius = (float) request()->query('radius', 10);

        $haversine = sprintf(
            '(6371 * acos(cos(radians(%f)) * cos(radians(latitude)) * cos(radians(longitude) - radians(%f)) + sin(radians(%f)) * sin(radians(latitude))))',
            $lat, $lng, $lat
        );

        $query = BarberModel::query()
            ->select('barbers.*')
            ->selectRaw("{$haversine} AS distance")
            ->where('is_freelancer', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->havingRaw('distance < ?', [$radius])
            ->orderBy('distance');

        $perPage = (int) request()->query('per_page', 20);
        $barbers = $query->paginate(min($perPage, 100));

        return $this->paginated(
            paginator: $barbers,
            resourceClass: BarberResource::class,
        );
    }
}
