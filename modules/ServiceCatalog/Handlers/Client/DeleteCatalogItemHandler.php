<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Handlers\Client;

use Modules\ServiceCatalog\CQRS\Command\Client\DeleteCatalogItemCommand;
use Modules\ServiceCatalog\Models\ServiceCatalogItemModel;

final class DeleteCatalogItemHandler
{
    public function handle(DeleteCatalogItemCommand $command): bool
    {
        $item = ServiceCatalogItemModel::findOrFail($command->id);

        return (bool) $item->delete();
    }
}
