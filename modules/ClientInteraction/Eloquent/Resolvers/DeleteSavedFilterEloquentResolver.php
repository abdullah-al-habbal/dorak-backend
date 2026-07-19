<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Eloquent\Resolvers;

use Modules\ClientInteraction\Models\ClientSavedFilterModel;

final class DeleteSavedFilterEloquentResolver
{
    public function resolve(string $filterId, string $clientId): void
    {
        ClientSavedFilterModel::where('id', $filterId)
            ->where('client_id', $clientId)
            ->delete();
    }
}
