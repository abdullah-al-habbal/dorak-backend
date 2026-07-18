<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Handlers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use Modules\ServiceCatalog\CQRS\Query\ListCatalogItemsQuery;
use Modules\ServiceCatalog\Models\ServiceCatalogItemModel;

final class ListCatalogItemsHandler
{
    public function handle(object $payload): LengthAwarePaginator
    {
        assert($payload instanceof ListCatalogItemsQuery);

        $query = ServiceCatalogItemModel::with(['category', 'tags']);

        if ($payload->categoryId !== null) {
            $query->where('category_id', $payload->categoryId);
        }

        if ($payload->search !== null) {
            $query->where(function ($q) use ($payload): void {
                $q->where('name->en', 'like', "%{$payload->search}%")
                    ->orWhere('name->ar', 'like', "%{$payload->search}%")
                    ->orWhere('slug', 'like', "%{$payload->search}%");
            });
        }

        return $query->paginate(min($payload->perPage, 100));
    }
}
