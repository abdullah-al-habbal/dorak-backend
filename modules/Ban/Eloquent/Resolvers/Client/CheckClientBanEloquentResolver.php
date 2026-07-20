<?php

declare(strict_types=1);

namespace Modules\Ban\Eloquent\Resolvers\Client;

use Modules\Ban\CQRS\Query\Client\CheckClientBanQuery;
use Modules\Client\Models\ClientModel;

final class CheckClientBanEloquentResolver
{
    public function resolve(CheckClientBanQuery $query): bool
    {
        $client = ClientModel::findOrFail($query->clientId);

        return $client->isBanned();
    }
}
