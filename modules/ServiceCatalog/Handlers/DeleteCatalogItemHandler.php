<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Handlers;


use Modules\ServiceCatalog\CQRS\Command\DeleteCatalogItemCommand;
use Modules\ServiceCatalog\Models\ServiceCatalogItemModel;

final class DeleteCatalogItemHandler
{
    public function handle(object $payload): bool
    {
        assert($payload instanceof DeleteCatalogItemCommand);

        $item = ServiceCatalogItemModel::findOrFail($payload->id);

        return (bool) $item->delete();
    }
}
