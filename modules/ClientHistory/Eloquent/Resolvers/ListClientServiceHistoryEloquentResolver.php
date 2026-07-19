<?php

declare(strict_types=1);

namespace Modules\ClientHistory\Eloquent\Resolvers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\ClientHistory\CQRS\Query\ListClientServiceHistoryQuery;
use Modules\ClientHistory\Models\ClientServiceHistoryModel;

final class ListClientServiceHistoryEloquentResolver
{
    public function resolve(ListClientServiceHistoryQuery $query): LengthAwarePaginator
    {
        $builder = ClientServiceHistoryModel::with(['barber', 'branch', 'catalogItem', 'media'])
            ->where('client_id', $query->clientId)
            ->orderBy('performed_at', 'desc');

        if ($query->catalogItemId !== null) {
            $builder->where('catalog_item_id', $query->catalogItemId);
        }

        return $builder->paginate($query->perPage);
    }
}
