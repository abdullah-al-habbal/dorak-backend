<?php

declare(strict_types=1);

namespace Modules\Client\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\Client\Handlers\Client\RegisterHandler;
use Modules\Client\Http\Requests\Client\RegisterRequest;
use Modules\Core\Http\Actions\BaseApiAction;

final class RegisterAction extends BaseApiAction
{
    public function __construct(
        private readonly RegisterHandler $handler,
    ) {}

    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $result = $this->handler->handle($request->toCommand());

        return $this->created(data: [
            'token' => $result->token,
            'client' => $result->client,
        ]);
    }
}