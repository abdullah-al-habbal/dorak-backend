<?php

declare(strict_types=1);

namespace Modules\Client\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Client\Handlers\Client\RefreshTokenHandler;
use Modules\Core\Http\Actions\BaseApiAction;

final class RefreshTokenAction extends BaseApiAction
{
    public function __construct(
        private readonly RefreshTokenHandler $handler,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $result = $this->handler->handle(client: $request->user());

        return $this->success(data: ['token' => $result->token]);
    }
}
