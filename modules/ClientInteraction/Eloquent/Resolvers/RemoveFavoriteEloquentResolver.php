<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Eloquent\Resolvers;

use Modules\ClientInteraction\Models\ClientFavoriteModel;

final class RemoveFavoriteEloquentResolver
{
    public function resolve(string $favoriteId, string $clientId): void
    {
        $favorite = ClientFavoriteModel::where('id', $favoriteId)
            ->where('client_id', $clientId)
            ->firstOrFail();

        $favorite->delete();
    }
}
