<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Eloquent\Resolvers;

use Illuminate\Database\Eloquent\Collection;
use Modules\ClientInteraction\CQRS\Query\ListSavedFiltersQuery;
use Modules\ClientInteraction\Models\ClientSavedFilterModel;

final class ListSavedFiltersEloquentResolver
{
    public function resolve(ListSavedFiltersQuery $query): Collection
    {
        return ClientSavedFilterModel::where('client_id', $query->clientId)
            ->orderByDesc('created_at')
            ->get();
    }
}
