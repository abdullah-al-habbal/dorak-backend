<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\ServiceCatalog\CQRS\Command\DeleteCatalogItemCommand;
use Modules\ServiceCatalog\Handlers\DeleteCatalogItemHandler;

final class DeleteCatalogItemAction extends BaseApiAction
{
    public function __construct(
        private readonly DeleteCatalogItemHandler $handler,
    ) {}

    public function __invoke(int $id): JsonResponse
    {
        $command = new DeleteCatalogItemCommand($id);
        $this->handler->handle($command);

        return $this->noContent();
    }
}
