<?php

declare(strict_types=1);

namespace Modules\Client\Handlers\Client;

use Illuminate\Support\Facades\Hash;
use Modules\Client\CQRS\Command\Client\LoginCommand;
use Modules\Client\Repositories\LoginEloquentResolver;
use Modules\Client\ValuesObjects\LoginResult;

final class LoginHandler
{
    public function __construct(
        private readonly LoginEloquentResolver $resolver,
    ) {}

    public function handle(LoginCommand $command): LoginResult
    {
        $client = $this->resolver->findByEmail($command->email);

        if ($client === null || ! Hash::check($command->password, $client->password)) {
            return LoginResult::invalidCredentials();
        }

        $token = $client->createToken('client-app')->plainTextToken;

        return LoginResult::success(
            token: $token,
            clientData: [
                'id' => $client->id,
                'name' => $client->name,
                'email' => $client->email,
                'phone' => $client->phone,
            ],
        );
    }
}
