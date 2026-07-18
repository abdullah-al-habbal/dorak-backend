<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Http\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\ServiceCatalog\CQRS\Query\ListCatalogItemsQuery;
use Modules\ServiceCatalog\Handlers\ListCatalogItemsHandler;
use Modules\ServiceCatalog\Http\Resources\ServiceCatalogItemResource;

final class ListCatalogItemsAction extends BaseApiAction
{
    public function __construct(
        private readonly ListCatalogItemsHandler $handler,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $query = new ListCatalogItemsQuery(
            categoryId: $request->query('category_id') ? (int) $request->query('category_id') : null,
            search: $request->query('search'),
            perPage: (int) $request->query('per_page', 20),
        );

        $items = $this->handler->handle($query);

        return $this->paginated(
            paginator: $items,
            resourceClass: ServiceCatalogItemResource::class,
        );
    }
}
