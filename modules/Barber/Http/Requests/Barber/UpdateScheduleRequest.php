<?php

declare(strict_types=1);

namespace Modules\Barber\Http\Requests\Barber;

use Modules\Barber\CQRS\Command\Barber\UpdateScheduleCommand;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class UpdateScheduleRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'schedule' => ['required', 'array', 'min:1'],
            'schedule.*.day_of_week' => ['required', 'integer', 'between:0,6'],
            'schedule.*.start_time' => ['required', 'date_format:H:i'],
            'schedule.*.end_time' => ['required', 'date_format:H:i', 'after:schedule.*.start_time'],
            'schedule.*.is_active' => ['required', 'boolean'],
        ];
    }

    public function toCommand(): UpdateScheduleCommand
    {
        return new UpdateScheduleCommand(
            barberId: (string) $this->user()->id,
            schedule: $this->validated('schedule'),
        );
    }
}
