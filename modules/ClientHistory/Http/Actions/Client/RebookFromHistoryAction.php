<?php

declare(strict_types=1);

namespace Modules\ClientHistory\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\ClientHistory\Handlers\RebookFromHistoryHandler;
use Modules\ClientHistory\Http\Requests\Client\RebookFromHistoryRequest;
use Modules\Core\Http\Actions\BaseApiAction;

final class RebookFromHistoryAction extends BaseApiAction
{
    public function __construct(
        private readonly RebookFromHistoryHandler $handler,
    ) {}

    public function __invoke(RebookFromHistoryRequest $request, string $history): JsonResponse
    {
        $booking = $this->handler->handle($request->toCommand($history));

        return $this->created(data: [
            'id' => $booking->id,
            'time_slot' => $booking->time_slot->toIso8601String(),
            'status' => $booking->status->value,
            'barber_id' => $booking->barber_id,
        ]);
    }
}
