<?php

declare(strict_types=1);

namespace Modules\Booking\Http\Requests;

use Modules\Core\Http\Requests\BaseApiFormRequest;

final class CreateBookingRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'chair_id' => ['required', 'string', 'exists:chairs,id'],
            'barber_id' => ['nullable', 'string', 'exists:barbers,id'],
            'time_slot' => ['required', 'date_format:Y-m-d H:i:s', 'after:now'],
            'service_ids' => ['nullable', 'array'],
            'service_ids.*' => ['string', 'exists:offered_services,id'],
        ];
    }
}
