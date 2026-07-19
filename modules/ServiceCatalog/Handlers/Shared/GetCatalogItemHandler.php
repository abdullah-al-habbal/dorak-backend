<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Handlers\Shared;


use Modules\ServiceCatalog\CQRS\Query\Shared\GetCatalogItemQuery;
use Modules\ServiceCatalog\Models\ServiceCatalogItemModel;

final class GetCatalogItemHandler
{
    public function handle(GetCatalogItemQuery $queyr): ?ServiceCatalogItemModel
    {
        return ServiceCatalogItemModel::with(['category', 'tags', 'media'])
            ->find($queyr->id);
    }
}
