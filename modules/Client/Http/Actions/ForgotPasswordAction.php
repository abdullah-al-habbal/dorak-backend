<?php

declare(strict_types=1);

namespace Modules\Client\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Client\Handlers\ForgotPasswordHandler;
use Modules\Client\Http\Requests\ForgotPasswordRequest;
use Modules\Core\Http\Actions\BaseApiAction;

final class ForgotPasswordAction extends BaseApiAction
{
    public function __construct(
        private readonly ForgotPasswordHandler $handler,
    ) {}

    public function __invoke(ForgotPasswordRequest $request): JsonResponse
    {
        $this->handler->handle(email: $request->validated('email'));

        return $this->success(message: $this->trans('core::messages.reset_code_sent'));
    }
}
