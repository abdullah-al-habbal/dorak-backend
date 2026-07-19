<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Eloquent\Resolvers;

use Modules\ClientInteraction\CQRS\Query\GetSavedFilterQuery;
use Modules\ClientInteraction\Models\ClientSavedFilterModel;

final class GetSavedFilterEloquentResolver
{
    public function resolve(GetSavedFilterQuery $query): ClientSavedFilterModel
    {
        return ClientSavedFilterModel::where('id', $query->filterId)
            ->where('client_id', $query->clientId)
            ->firstOrFail();
    }
}
