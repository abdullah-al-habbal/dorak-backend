<?php

declare(strict_types=1);

namespace Modules\Client\Eloquent\Resolvers\Client;

use Modules\Client\Models\ClientModel;

final class ResetPasswordEloquentResolver
{
    public function findByEmail(string $email): ClientModel
    {
        return ClientModel::where('email', $email)->firstOrFail();
    }

    public function updatePassword(ClientModel $client, string $hashedPassword): void
    {
        $client->update(['password' => $hashedPassword]);
    }

    public function deleteTokens(ClientModel $client): void
    {
        $client->tokens()->delete();
    }
}
