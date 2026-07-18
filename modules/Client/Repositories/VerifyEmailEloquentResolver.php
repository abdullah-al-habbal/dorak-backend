<?php

declare(strict_types=1);

namespace Modules\Client\Repositories;

use Modules\Client\Models\ClientModel;

final class VerifyEmailEloquentResolver
{
    public function markAsVerified(ClientModel $client): void
    {
        $client->update(['email_verified_at' => now()]);
    }
}
