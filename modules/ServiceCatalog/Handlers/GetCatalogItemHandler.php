<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Handlers;

use Modules\Core\Handlers\BaseHandler;
use Modules\ServiceCatalog\CQRS\Query\GetCatalogItemQuery;
use Modules\ServiceCatalog\Models\ServiceCatalogItemModel;

final class GetCatalogItemHandler extends BaseHandler
{
    public function handle(object $payload): ?ServiceCatalogItemModel
    {
        assert($payload instanceof GetCatalogItemQuery);

        return ServiceCatalogItemModel::with(['category', 'tags', 'media'])
            ->find($payload->id);
    }
}
