<?php

declare(strict_types=1);

namespace Modules\Client\Repositories;

use Modules\Client\Models\ClientModel;

final class LogoutEloquentResolver
{
    public function deleteCurrentToken(ClientModel $client): void
    {
        $client->currentAccessToken()->delete();
    }
}
