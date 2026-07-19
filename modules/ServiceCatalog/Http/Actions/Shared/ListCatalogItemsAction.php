<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Http\Actions\Shared;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\ServiceCatalog\Handlers\Shared\ListCatalogItemsHandler;
use Modules\ServiceCatalog\Http\Requests\Shared\ListCatalogItemsRequest;
use Modules\ServiceCatalog\Http\Resources\Shared\ServiceCatalogItemResource;

final class ListCatalogItemsAction extends BaseApiAction
{
    public function __construct(
        private readonly ListCatalogItemsHandler $handler,
    ) {}

    public function __invoke(ListCatalogItemsRequest $request): JsonResponse
    {
        $query = $request->toQuery();
        $items = $this->handler->handle($query);

        return $this->paginated(
            paginator: $items,
            resourceClass: ServiceCatalogItemResource::class,
        );
    }
}
