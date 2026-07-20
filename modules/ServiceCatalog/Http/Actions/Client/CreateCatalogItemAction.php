<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\ServiceCatalog\Handlers\Client\CreateCatalogItemHandler;
use Modules\ServiceCatalog\Http\Requests\Client\CreateCatalogItemRequest;
use Modules\ServiceCatalog\Http\Resources\Shared\ServiceCatalogItemResource;

final class CreateCatalogItemAction extends BaseApiAction
{
    public function __construct(
        private readonly CreateCatalogItemHandler $handler,
    ) {}

    public function __invoke(CreateCatalogItemRequest $request): JsonResponse
    {
        $item = $this->handler->handle($request->toCommand());

        $item->load(['category', 'tags']);

        return $this->created(
            data: new ServiceCatalogItemResource($item),
        );
    }
}
