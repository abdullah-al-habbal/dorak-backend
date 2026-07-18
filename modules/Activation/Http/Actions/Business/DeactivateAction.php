<?php

declare(strict_types=1);

namespace Modules\Activation\Http\Actions\Business;

use Illuminate\Http\JsonResponse;
use Modules\Activation\CQRS\Command\Business\ToggleActivationCommand;
use Modules\Activation\Handlers\Business\ToggleActivationHandler;
use Modules\Activation\Http\Requests\Business\ToggleActivationRequest;
use Modules\Activation\Http\Resources\Business\ActivationLogResource;
use Modules\Core\Http\Actions\BaseApiAction;

final class DeactivateAction extends BaseApiAction
{
    public function __construct(
        private readonly ToggleActivationHandler $handler,
    ) {}

    public function __invoke(ToggleActivationRequest $request, string $barber): JsonResponse
    {
        $command = new ToggleActivationCommand(
            barberId: $barber,
            activate: false,
            reason: $request->validated('reason'),
        );

        $log = $this->handler->handle($command);

        return $this->created(
            data: new ActivationLogResource($log),
            message: 'Barber deactivated successfully',
        );
    }
}
