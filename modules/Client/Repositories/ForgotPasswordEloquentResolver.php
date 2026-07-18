<?php

declare(strict_types=1);

namespace Modules\Client\Repositories;

use Modules\Client\Models\ClientModel;

final class ForgotPasswordEloquentResolver
{
    public function findByEmail(string $email): ?ClientModel
    {
        return ClientModel::where('email', $email)->first();
    }
}
