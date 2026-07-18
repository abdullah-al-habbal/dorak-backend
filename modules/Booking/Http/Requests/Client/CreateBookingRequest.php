<?php

declare(strict_types=1);

namespace Modules\Booking\Http\Requests\Client;

use Carbon\Carbon;
use Modules\Booking\CQRS\Command\Client\CreateBookingCommand;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class CreateBookingRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'chair_id' => ['nullable', 'string', 'exists:chairs,id', 'required_without:at_home_location', 'prohibits:at_home_location'],
            'barber_id' => ['nullable', 'string', 'exists:barbers,id'],
            'time_slot' => ['required', 'date_format:Y-m-d H:i:s', 'after:now'],
            'service_ids' => ['nullable', 'array'],
            'service_ids.*' => ['string', 'exists:offered_services,id'],
            'at_home_location' => ['nullable', 'array', 'required_without:chair_id', 'prohibits:chair_id'],
            'at_home_location.lat' => ['required_with:at_home_location', 'numeric', 'between:-90,90'],
            'at_home_location.lng' => ['required_with:at_home_location', 'numeric', 'between:-180,180'],
        ];
    }

    public function toCommand(): CreateBookingCommand
    {
        return new CreateBookingCommand(
            chairId: $this->validated('chair_id'),
            barberId: $this->validated('barber_id'),
            clientId: $this->user()->id,
            timeSlot: Carbon::parse($this->validated('time_slot')),
            serviceIds: $this->validated('service_ids', []),
            atHomeLocation: $this->validated('at_home_location'),
        );
    }
}
