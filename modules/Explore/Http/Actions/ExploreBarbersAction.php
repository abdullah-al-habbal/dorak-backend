<?php

declare(strict_types=1);

namespace Modules\Explore\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Barber\Http\Resources\BarberResource;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\Explore\Handlers\ExploreBarbersHandler;
use Modules\Explore\Http\Requests\ExploreBarbersRequest;

final class ExploreBarbersAction extends BaseApiAction
{
    public function __construct(
        private readonly ExploreBarbersHandler $handler,
    ) {}

    public function __invoke(ExploreBarbersRequest $request): JsonResponse
    {
        $lat = (float) $request->query('lat', 0);
        $lng = (float) $request->query('lng', 0);
        $radius = (float) $request->query('radius', 10);
        $perPage = (int) $request->query('per_page', 20);

        $barbers = $this->handler->handle($lat, $lng, $radius, $perPage);

        return $this->paginated(paginator: $barbers, resourceClass: BarberResource::class);
    }
}
