<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Eloquent\Resolvers;

use Modules\ClientInteraction\CQRS\Command\UpdateSavedFilterCommand;
use Modules\ClientInteraction\Models\ClientSavedFilterModel;

final class UpdateSavedFilterEloquentResolver
{
    public function resolve(UpdateSavedFilterCommand $command): ClientSavedFilterModel
    {
        $filter = ClientSavedFilterModel::where('id', $command->filterId)
            ->where('client_id', $command->clientId)
            ->firstOrFail();

        $filter->update([
            'name' => $command->name,
            'filter_config' => $command->filterConfig,
        ]);

        return $filter;
    }
}
