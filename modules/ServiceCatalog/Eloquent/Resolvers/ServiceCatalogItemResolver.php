<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Eloquent\Resolvers;

use Modules\ServiceCatalog\Models\ServiceCatalogItemModel;

final class ServiceCatalogItemResolver
{
    public static function resolveById(int $id): ?ServiceCatalogItemModel
    {
        return ServiceCatalogItemModel::with(['category', 'tags', 'media'])
            ->find($id);
    }

    public static function resolveBySlug(string $slug): ?ServiceCatalogItemModel
    {
        return ServiceCatalogItemModel::with(['category', 'tags', 'media'])
            ->where('slug', $slug)
            ->first();
    }
}
