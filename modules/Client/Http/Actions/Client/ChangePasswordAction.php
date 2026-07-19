<?php

declare(strict_types=1);

namespace Modules\Client\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\Client\Handlers\Client\ChangePasswordHandler;
use Modules\Client\Http\Requests\Client\ChangePasswordRequest;
use Modules\Core\Http\Actions\BaseApiAction;

final class ChangePasswordAction extends BaseApiAction
{
    public function __construct(
        private readonly ChangePasswordHandler $handler,
    ) {}

    public function __invoke(ChangePasswordRequest $request): JsonResponse
    {
        $this->handler->handle($request->toCommand());

        return $this->success(message: $this->trans('core::messages.password_changed'));
    }
}
