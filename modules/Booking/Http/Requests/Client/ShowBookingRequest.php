<?php

declare(strict_types=1);

namespace Modules\Booking\Http\Requests\Client;

use Modules\Booking\CQRS\Query\Client\ShowBookingQuery;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class ShowBookingRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [];
    }

    public function toQuery(string $bookingId): ShowBookingQuery
    {
        return new ShowBookingQuery(
            bookingId: $bookingId,
            clientId: (string) $this->user()->id,
        );
    }
}
