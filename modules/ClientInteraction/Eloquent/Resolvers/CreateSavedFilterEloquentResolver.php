<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Eloquent\Resolvers;

use Modules\ClientInteraction\CQRS\Command\CreateSavedFilterCommand;
use Modules\ClientInteraction\Models\ClientSavedFilterModel;

final class CreateSavedFilterEloquentResolver
{
    public function resolve(CreateSavedFilterCommand $command): ClientSavedFilterModel
    {
        return ClientSavedFilterModel::create([
            'client_id' => $command->clientId,
            'name' => $command->name,
            'filter_config' => $command->filterConfig,
        ]);
    }
}
