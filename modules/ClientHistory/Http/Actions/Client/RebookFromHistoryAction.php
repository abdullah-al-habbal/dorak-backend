<?php

declare(strict_types=1);

namespace Modules\ClientHistory\Http\Actions\Client;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Modules\ClientHistory\CQRS\Command\RebookFromHistoryCommand;
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
        $command = new RebookFromHistoryCommand(
            historyId: $history,
            clientId: $request->user()->id,
            timeSlot: new Carbon((string) $request->input('time_slot')),
        );

        $booking = $this->handler->handle($command);

        return $this->created(data: [
            'id' => $booking->id,
            'time_slot' => $booking->time_slot->toIso8601String(),
            'status' => $booking->status->value,
            'barber_id' => $booking->barber_id,
        ]);
    }
}
