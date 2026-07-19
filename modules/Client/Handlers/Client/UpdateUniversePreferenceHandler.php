<?php

declare(strict_types=1);

namespace Modules\Client\Handlers\Client;

use Modules\Client\CQRS\Command\Client\UpdateUniversePreferenceCommand;
use Modules\Client\Models\ClientModel;
use Modules\Client\Repositories\UpdateUniversePreferenceEloquentResolver;
use Modules\Client\ValuesObjects\UpdateUniversePreferenceResult;

final class UpdateUniversePreferenceHandler
{
    public function __construct(
        private readonly UpdateUniversePreferenceEloquentResolver $resolver,
    ) {}

    public function handle(UpdateUniversePreferenceCommand $command): UpdateUniversePreferenceResult
    {
        $client = ClientModel::findOrFail($command->clientId);

        $this->resolver->updateUniverse($client, $command->universe);

        return UpdateUniversePreferenceResult::success($command->universe);
    }
}
