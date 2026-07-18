<?php

declare(strict_types=1);

namespace Modules\Client\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Client\Handlers\DeleteAccountHandler;
use Modules\Client\Http\Requests\DeleteAccountRequest;
use Modules\Core\Enums\ErrorCodeEnum;
use Modules\Core\Http\Actions\BaseApiAction;

final class DeleteAccountAction extends BaseApiAction
{
    public function __construct(
        private readonly DeleteAccountHandler $handler,
    ) {}

    public function __invoke(
        DeleteAccountRequest $request,
    ): JsonResponse {
        $client = $request->user();
        $password = $request->validated('password');

        $result = $this->handler->handle($client, $password);

        if ($result->isInvalidCredentials()) {
            return $this->unauthorized(
                message: $this->trans('core::messages.invalid_credentials'),
            );
        }

        if ($result->hasActiveBookings()) {
            return $this->businessError(
                code: ErrorCodeEnum::UNPROCESSABLE_ENTITY,
                message: $this->trans('core::messages.active_bookings_block_deletion'),
            );
        }

        return $this->noContent();
    }
}
