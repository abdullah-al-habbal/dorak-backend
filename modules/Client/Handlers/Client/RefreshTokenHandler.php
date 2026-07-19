<?php

declare(strict_types=1);

namespace Modules\Client\Handlers\Client;

use Modules\Client\Models\ClientModel;
use Modules\Client\Repositories\RefreshTokenEloquentResolver;
use Modules\Client\ValuesObjects\RefreshTokenResult;

final class RefreshTokenHandler
{
    public function __construct(
        private readonly RefreshTokenEloquentResolver $resolver,
    ) {}

    public function handle(ClientModel $client): RefreshTokenResult
    {
        $this->resolver->deleteCurrentToken($client);

        $token = $client->createToken('client-app')->plainTextToken;

        return RefreshTokenResult::success($token);
    }
}
