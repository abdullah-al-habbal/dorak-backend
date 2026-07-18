<?php

declare(strict_types=1);

namespace Modules\Client\Repositories;

use Modules\Client\Models\ClientModel;

final class ResetPasswordEloquentResolver
{
    public function findByEmail(string $email): ?ClientModel
    {
        return ClientModel::where('email', $email)->first();
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
