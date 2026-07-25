<?php

declare(strict_types=1);

namespace Modules\Branch\Http\Actions\Branch;

use Illuminate\Http\JsonResponse;
use Modules\Branch\Handlers\Branch\LoginHandler;
use Modules\Branch\Http\Requests\Branch\LoginRequest;
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
            'branch' => $result->branch,
        ]);
    }
}
