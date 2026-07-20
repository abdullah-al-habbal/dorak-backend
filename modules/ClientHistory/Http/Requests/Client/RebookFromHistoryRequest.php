<?php

declare(strict_types=1);

namespace Modules\ClientHistory\Http\Requests\Client;

use Carbon\Carbon;
use Modules\ClientHistory\CQRS\Command\RebookFromHistoryCommand;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class RebookFromHistoryRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'time_slot' => ['required', 'date', 'after:now'],
        ];
    }

    public function toCommand(string $historyId): RebookFromHistoryCommand
    {
        return new RebookFromHistoryCommand(
            historyId: $historyId,
            clientId: (string) $this->user()->id,
            timeSlot: new Carbon((string) $this->validated('time_slot')),
        );
    }
}
