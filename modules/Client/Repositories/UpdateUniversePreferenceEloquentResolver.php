<?php

declare(strict_types=1);

namespace Modules\Client\Repositories;

use Modules\Client\Enums\UniverseEnum;
use Modules\Client\Models\ClientModel;
use Modules\Core\Enums\Universe;

final class UpdateUniversePreferenceEloquentResolver
{
    public function updateUniverse(ClientModel $client, UniverseEnum $universe): void
    {
        $client->update(['preferred_universe' => Universe::from($universe->value)]);
    }
}
