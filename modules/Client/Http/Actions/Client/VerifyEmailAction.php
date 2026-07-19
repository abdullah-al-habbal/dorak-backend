<?php

declare(strict_types=1);

namespace Modules\Client\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\Client\Handlers\Client\VerifyEmailHandler;
use Modules\Client\Http\Requests\Client\VerifyEmailRequest;
use Modules\Core\Http\Actions\BaseApiAction;

final class VerifyEmailAction extends BaseApiAction
{
    public function __construct(
        private readonly VerifyEmailHandler $handler,
    ) {}

    public function __invoke(VerifyEmailRequest $request): JsonResponse
    {
        $result = $this->handler->handle(
            client: $request->user(),
            code: $request->validated('code'),
        );

        if ($result->isAlreadyVerified()) {
            return $this->success(message: $this->trans('core::messages.email_already_verified'));
        }

        if ($result->isInvalidCode()) {
            return $this->unprocessable(message: $this->trans('core::messages.invalid_verification_code'));
        }

        return $this->success(message: $this->trans('core::messages.email_verified'));
    }
}
