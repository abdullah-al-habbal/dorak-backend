<?php

declare(strict_types=1);

namespace Modules\Barber\Http\Actions\Barber;

use Illuminate\Http\JsonResponse;
use Modules\Barber\Handlers\Barber\LoginHandler;
use Modules\Barber\Http\Requests\Barber\LoginRequest;
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
            'barber' => $result->barber,
        ]);
    }
}
