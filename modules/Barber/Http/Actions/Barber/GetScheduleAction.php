<?php

declare(strict_types=1);

namespace Modules\Barber\Http\Actions\Barber;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Barber\CQRS\Query\Barber\GetScheduleQuery;
use Modules\Barber\Handlers\Barber\GetScheduleHandler;
use Modules\Core\Http\Actions\BaseApiAction;

final class GetScheduleAction extends BaseApiAction
{
    public function __construct(
        private readonly GetScheduleHandler $handler,
    ) {}

    public function __invoke(): JsonResponse
    {
        $schedule = $this->handler->handle(new GetScheduleQuery(
            barberId: (string) Auth::guard('barber')->id(),
        ));

        return $this->ok(data: $schedule);
    }
}
