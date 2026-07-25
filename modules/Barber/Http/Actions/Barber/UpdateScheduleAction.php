<?php

declare(strict_types=1);

namespace Modules\Barber\Http\Actions\Barber;

use Illuminate\Http\JsonResponse;
use Modules\Barber\Handlers\Barber\UpdateScheduleHandler;
use Modules\Barber\Http\Requests\Barber\UpdateScheduleRequest;
use Modules\Core\Http\Actions\BaseApiAction;

final class UpdateScheduleAction extends BaseApiAction
{
    public function __construct(
        private readonly UpdateScheduleHandler $handler,
    ) {}

    public function __invoke(UpdateScheduleRequest $request): JsonResponse
    {
        $schedule = $this->handler->handle($request->toCommand());

        return $this->ok(data: $schedule);
    }
}
