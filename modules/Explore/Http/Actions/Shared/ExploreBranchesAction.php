<?php

declare(strict_types=1);

namespace Modules\Explore\Http\Actions\Shared;

use Illuminate\Http\JsonResponse;
use Modules\Branch\Http\Resources\Shared\BranchResource;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\Explore\Handlers\Shared\ExploreBranchesHandler;
use Modules\Explore\Http\Requests\Shared\ExploreBranchesRequest;

final class ExploreBranchesAction extends BaseApiAction
{
    public function __construct(
        private readonly ExploreBranchesHandler $handler,
    ) {}

    public function __invoke(ExploreBranchesRequest $request): JsonResponse
    {
        $query = $request->toQuery();
        $branches = $this->handler->handle($query);

        return $this->paginated(paginator: $branches, resourceClass: BranchResource::class);
    }
}
