<?php

declare(strict_types=1);

namespace Modules\Brand\Http\Actions\Shared;

use Illuminate\Http\JsonResponse;
use Modules\Brand\Handlers\Shared\ListBrandsHandler;
use Modules\Brand\Http\Requests\Shared\ListBrandsRequest;
use Modules\Brand\Http\Resources\Shared\BrandResource;
use Modules\Core\Http\Actions\BaseApiAction;

final class ListBrandsAction extends BaseApiAction
{
    public function __construct(
        private readonly ListBrandsHandler $handler,
    ) {}

    public function __invoke(ListBrandsRequest $request): JsonResponse
    {
        $query = $request->toQuery();
        $result = $this->handler->handle($query);

        return $this->paginated(
            paginator: $result,
            resourceClass: BrandResource::class,
        );
    }
}
