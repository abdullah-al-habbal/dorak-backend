<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Eloquent\Resolvers;

use Modules\ClientInteraction\CQRS\Command\AddFavoriteCommand;
use Modules\ClientInteraction\Models\ClientFavoriteModel;

final class AddFavoriteEloquentResolver
{
    public function resolve(AddFavoriteCommand $command): ClientFavoriteModel
    {
        return ClientFavoriteModel::create([
            'client_id' => $command->clientId,
            'favorable_id' => $command->favorableId,
            'favorable_type' => $command->favorableType,
        ]);
    }
}
