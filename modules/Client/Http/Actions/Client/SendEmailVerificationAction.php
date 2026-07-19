<?php

declare(strict_types=1);

namespace Modules\Client\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Client\Handlers\Client\SendEmailVerificationHandler;
use Modules\Core\Http\Actions\BaseApiAction;

final class SendEmailVerificationAction extends BaseApiAction
{
    public function __construct(
        private readonly SendEmailVerificationHandler $handler,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $result = $this->handler->handle(client: $request->user());

        if ($result->isAlreadyVerified()) {
            return $this->success(message: $this->trans('core::messages.email_already_verified'));
        }

        return $this->success(message: $this->trans('core::messages.verification_code_sent'));
    }
}
