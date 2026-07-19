<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\ServiceCatalog\Handlers\Client\UpdateCatalogItemHandler;
use Modules\ServiceCatalog\Http\Requests\Client\UpdateCatalogItemRequest;
use Modules\ServiceCatalog\Http\Resources\Shared\ServiceCatalogItemResource;

final class UpdateCatalogItemAction extends BaseApiAction
{
    public function __construct(
        private readonly UpdateCatalogItemHandler $handler,
    ) {}

    public function __invoke(int $id, UpdateCatalogItemRequest $request): JsonResponse
    {
        $command = $request->toCommand($id);
        $item = $this->handler->handle($command);

        return $this->ok(
            data: new ServiceCatalogItemResource($item),
        );
    }
}
