<?php

declare(strict_types=1);

namespace Modules\Client\Handlers\Client;

use Modules\Client\CQRS\Command\Client\RegisterCommand;
use Modules\Client\Eloquent\Resolvers\Client\RegisterEloquentResolver;
use Modules\Client\ValuesObjects\RegisterResult;

final class RegisterHandler
{
    public function __construct(
        private readonly RegisterEloquentResolver $resolver,
    ) {}

    public function handle(RegisterCommand $command): RegisterResult
    {
        $client = $this->resolver->resolve($command);

        $token = $client->createToken('client-app')->plainTextToken;

        return RegisterResult::success(
            $token,
            [
                'id' => $client->id,
                'name' => $client->name,
                'email' => $client->email,
                'phone' => $client->phone,
            ],
        );
    }
}
