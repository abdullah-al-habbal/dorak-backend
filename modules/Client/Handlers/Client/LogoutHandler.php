<?php

declare(strict_types=1);

namespace Modules\Client\Handlers\Client;

use Modules\Client\Models\ClientModel;
use Modules\Client\Repositories\LogoutEloquentResolver;
use Modules\Client\ValuesObjects\LogoutResult;

final class LogoutHandler
{
    public function __construct(
        private readonly LogoutEloquentResolver $resolver,
    ) {}

    public function handle(ClientModel $client): LogoutResult
    {
        $this->resolver->deleteCurrentToken($client);

        return LogoutResult::success();
    }
}
