<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Eloquent\Resolvers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\ClientInteraction\CQRS\Query\ListFavoritesQuery;
use Modules\ClientInteraction\Models\ClientFavoriteModel;

final class ListFavoritesEloquentResolver
{
    public function resolve(ListFavoritesQuery $query): LengthAwarePaginator
    {
        return ClientFavoriteModel::where('client_id', $query->clientId)
            ->when($query->favorableType, fn ($q, $type) => $q->where('favorable_type', $type))
            ->with('favorable')
            ->orderByDesc('created_at')
            ->paginate($query->perPage);
    }
}
