<?php

declare(strict_types=1);

namespace Modules\Client\Repositories;

use Modules\Client\Models\ClientModel;

final class UpdateUniversePreferenceEloquentResolver
{
    public function updateUniverse(ClientModel $client, string $universe): void
    {
        $client->update(['preferred_universe' => $universe]);
    }
}
