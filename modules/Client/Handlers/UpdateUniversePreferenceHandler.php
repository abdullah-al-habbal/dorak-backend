<?php

declare(strict_types=1);

namespace Modules\Client\Handlers;

use Modules\Client\Models\ClientModel;
use Modules\Client\Repositories\UpdateUniversePreferenceEloquentResolver;
use Modules\Client\ValuesObjects\UpdateUniversePreferenceResult;

final class UpdateUniversePreferenceHandler
{
    public function __construct(
        private readonly UpdateUniversePreferenceEloquentResolver $resolver,
    ) {}

    public function handle(ClientModel $client, string $universe): UpdateUniversePreferenceResult
    {
        $this->resolver->updateUniverse($client, $universe);

        return UpdateUniversePreferenceResult::success($universe);
    }
}
