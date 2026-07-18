<?php

declare(strict_types=1);

namespace Modules\Client\Handlers;

use Illuminate\Support\Facades\Hash;
use Modules\Client\Repositories\LoginEloquentResolver;
use Modules\Client\ValuesObjects\LoginResult;

final class LoginHandler
{
    public function __construct(
        private readonly LoginEloquentResolver $resolver,
    ) {}

    public function handle(string $email, string $password): LoginResult
    {
        $client = $this->resolver->findByEmail($email);

        if ($client === null || !Hash::check($password, $client->password)) {
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
