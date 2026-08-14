<?php

declare(strict_types=1);

namespace Modules\Client\Eloquent\Resolvers\Client;

use Illuminate\Support\Facades\Hash;
use Modules\Client\CQRS\Command\Client\RegisterCommand;
use Modules\Client\Models\ClientModel;

final class RegisterEloquentResolver
{
    public function resolve(RegisterCommand $command): ClientModel
    {
        return ClientModel::create([
            'name' => $command->name,
            'email' => $command->email,
            'password' => Hash::make($command->password),
            'phone' => $command->phone,
        ]);
    }
}
