<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Eloquent\Resolvers\Shared;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\ServiceCatalog\CQRS\Query\Shared\ListCatalogItemsQuery;
use Modules\ServiceCatalog\Models\ServiceCatalogItemModel;

final class ListCatalogItemsEloquentResolver
{
    public function resolve(ListCatalogItemsQuery $payload): LengthAwarePaginator
    {
        $builder = ServiceCatalogItemModel::with(['category', 'tags']);

        if ($payload->categoryId !== null) {
            $builder->where('category_id', $payload->categoryId);
        }

        if ($payload->search !== null) {
            $builder->where(function ($q) use ($payload): void {
                $q->where('name->en', 'like', "%{$payload->search}%")
                    ->orWhere('name->ar', 'like', "%{$payload->search}%")
                    ->orWhere('slug', 'like', "%{$payload->search}%");
            });
        }

        return $builder->paginate($payload->perPage);
    }
}
