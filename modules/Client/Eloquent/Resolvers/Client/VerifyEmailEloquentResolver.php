<?php

declare(strict_types=1);

namespace Modules\Client\Eloquent\Resolvers\Client;

use Modules\Client\Models\ClientModel;

final class VerifyEmailEloquentResolver
{
    public function findById(string $clientId): ?ClientModel
    {
        return ClientModel::find($clientId);
    }

    public function markAsVerified(ClientModel $client): void
    {
        $client->update(['email_verified_at' => now()]);
    }
}
