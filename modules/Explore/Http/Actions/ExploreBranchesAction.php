<?php

declare(strict_types=1);

namespace Modules\Explore\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Branch\Http\Resources\BranchResource;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\Explore\Handlers\ExploreBranchesHandler;
use Modules\Explore\Http\Requests\ExploreBranchesRequest;

final class ExploreBranchesAction extends BaseApiAction
{
    public function __construct(
        private readonly ExploreBranchesHandler $handler,
    ) {}

    public function __invoke(ExploreBranchesRequest $request): JsonResponse
    {
        $lat = (float) $request->query('lat', 0);
        $lng = (float) $request->query('lng', 0);
        $radius = (float) $request->query('radius', 10);
        $universe = $request->query('universe');
        $perPage = (int) $request->query('per_page', 20);

        $branches = $this->handler->handle($lat, $lng, $radius, $universe, $perPage);

        return $this->paginated(paginator: $branches, resourceClass: BranchResource::class);
    }
}
