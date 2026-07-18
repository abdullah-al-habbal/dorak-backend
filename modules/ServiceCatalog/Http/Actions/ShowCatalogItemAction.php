<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\ServiceCatalog\CQRS\Query\GetCatalogItemQuery;
use Modules\ServiceCatalog\Handlers\GetCatalogItemHandler;
use Modules\ServiceCatalog\Http\Resources\ServiceCatalogItemResource;

final class ShowCatalogItemAction extends BaseApiAction
{
    public function __construct(
        private readonly GetCatalogItemHandler $handler,
    ) {}

    public function __invoke(int $id): JsonResponse
    {
        $query = new GetCatalogItemQuery($id);
        $item = $this->handler->handle($query);

        if ($item === null) {
            return $this->notFound();
        }

        return $this->ok(
            data: new ServiceCatalogItemResource($item),
        );
    }
}
