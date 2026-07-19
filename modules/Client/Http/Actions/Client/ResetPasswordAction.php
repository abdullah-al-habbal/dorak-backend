<?php

declare(strict_types=1);

namespace Modules\Client\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\Client\Handlers\Client\ResetPasswordHandler;
use Modules\Client\Http\Requests\Client\ResetPasswordRequest;
use Modules\Core\Http\Actions\BaseApiAction;

final class ResetPasswordAction extends BaseApiAction
{
    public function __construct(
        private readonly ResetPasswordHandler $handler,
    ) {}

    // todo: we need to refacor to use the Command and the toCommand()
    public function __invoke(ResetPasswordRequest $request): JsonResponse
    {
        $result = $this->handler->handle(
            email: $request->validated('email'),
            code: $request->validated('code'),
            password: $request->validated('password'),
        );

        if ($result->isInvalidCode()) {
            return $this->unprocessable(message: $this->trans('core::messages.invalid_reset_code'));
        }

        return $this->success(message: $this->trans('core::messages.password_reset'));
    }
}
