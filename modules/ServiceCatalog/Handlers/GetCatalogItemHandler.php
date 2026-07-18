<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Handlers;


use Modules\ServiceCatalog\CQRS\Query\GetCatalogItemQuery;
use Modules\ServiceCatalog\Models\ServiceCatalogItemModel;

final class GetCatalogItemHandler
{
    public function handle(GetCatalogItemQuery $queyr): ?ServiceCatalogItemModel
    {
        return ServiceCatalogItemModel::with(['category', 'tags', 'media'])
            ->find($queyr->id);
    }
}
