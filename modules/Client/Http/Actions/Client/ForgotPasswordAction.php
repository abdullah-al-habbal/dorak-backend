<?php

declare(strict_types=1);

namespace Modules\Client\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\Client\Handlers\Client\ForgotPasswordHandler;
use Modules\Client\Http\Requests\Client\ForgotPasswordRequest;
use Modules\Core\Http\Actions\BaseApiAction;

final class ForgotPasswordAction extends BaseApiAction
{
    public function __construct(
        private readonly ForgotPasswordHandler $handler,
    ) {}

    public function __invoke(ForgotPasswordRequest $request): JsonResponse
    {
        $this->handler->handle($request->toCommand());

        return $this->success(message: $this->trans('core::messages.reset_code_sent'));
    }
}
