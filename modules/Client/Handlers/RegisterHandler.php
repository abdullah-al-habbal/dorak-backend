<?php

declare(strict_types=1);

namespace Modules\Client\Handlers;

use Modules\Client\Repositories\RegisterEloquentResolver;
use Modules\Client\ValuesObjects\RegisterResult;

final class RegisterHandler
{
    public function __construct(
        private readonly RegisterEloquentResolver $resolver,
    ) {}

    public function handle(array $data): RegisterResult
    {
        $client = $this->resolver->create($data);

        $token = $client->createToken('client-app')->plainTextToken;

        return RegisterResult::success(
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
