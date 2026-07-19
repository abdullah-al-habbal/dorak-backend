<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Http\Actions\Shared;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\ServiceCatalog\CQRS\Query\Shared\GetCatalogItemQuery;
use Modules\ServiceCatalog\Handlers\Shared\GetCatalogItemHandler;
use Modules\ServiceCatalog\Http\Resources\Shared\ServiceCatalogItemResource;

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
