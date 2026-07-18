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
        $query = $request->toQuery();
        $barbers = $this->handler->handle($query);

        return $this->paginated(paginator: $barbers, resourceClass: BarberResource::class);
    }
}
