<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Handlers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use Modules\ServiceCatalog\CQRS\Query\ListCatalogItemsQuery;
use Modules\ServiceCatalog\Models\ServiceCatalogItemModel;

final class ListCatalogItemsHandler
{
    public function handle(ListCatalogItemsQuery $query): LengthAwarePaginator
    {
        $query = ServiceCatalogItemModel::with(['category', 'tags']);

        if ($query->categoryId !== null) {
            $query->where('category_id', $query->categoryId);
        }

        if ($query->search !== null) {
            $query->where(function ($q) use ($query): void {
                $q->where('name->en', 'like', "%{$query->search}%")
                    ->orWhere('name->ar', 'like', "%{$query->search}%")
                    ->orWhere('slug', 'like', "%{$query->search}%");
            });
        }

        return $query->paginate(min($query->perPage, 100));
    }
}
