<?php

declare(strict_types=1);

namespace Modules\Client\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\Client\Handlers\Client\LoginHandler;
use Modules\Client\Http\Requests\Client\LoginRequest;
use Modules\Core\Http\Actions\BaseApiAction;

final class LoginAction extends BaseApiAction
{
    public function __construct(
        private readonly LoginHandler $handler,
    ) {}

    public function __invoke(LoginRequest $request): JsonResponse
    {
        $result = $this->handler->handle($request->toCommand());

        if ($result->isInvalidCredentials()) {
            return $this->unauthorized(message: $this->trans('core::messages.invalid_credentials'));
        }

        return $this->success(data: [
            'token' => $result->token,
            'client' => $result->client,
        ]);
    }
}
