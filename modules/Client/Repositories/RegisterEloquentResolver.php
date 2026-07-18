<?php

declare(strict_types=1);

namespace Modules\Client\Repositories;

use Illuminate\Support\Facades\Hash;
use Modules\Client\Models\ClientModel;

final class RegisterEloquentResolver
{
    public function create(array $data): ClientModel
    {
        return ClientModel::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
        ]);
    }
}
