<?php

declare(strict_types=1);

namespace Modules\Barber\Repositories;

use Modules\Barber\Models\BarberModel;

final class LoginEloquentResolver
{
    public function findByEmail(string $email): ?BarberModel
    {
        return BarberModel::where('email', $email)->first();
    }
}
