<?php

declare(strict_types=1);

namespace Modules\Booking\Http\Requests\Client;

use Modules\Booking\CQRS\Command\Client\CancelBookingCommand;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class CancelBookingRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [];
    }

    public function toCommand(string $bookingId): CancelBookingCommand
    {
        return new CancelBookingCommand(
            bookingId: $bookingId,
            clientId: (string) $this->user()->id,
        );
    }
}
