<?php

declare(strict_types=1);

namespace Modules\Booking\Http\Requests;

use Modules\Booking\CQRS\Query\ListUserBookingsQuery;
use Modules\Booking\Enums\BookingFilterStatus;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class ListUserBookingsRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        // todo: use Enum instead of "in"
        return [
            'status' => ['nullable', 'string', 'in:upcoming,past'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function toQuery(): ListUserBookingsQuery
    {
        $status = $this->query('status');

        return new ListUserBookingsQuery(
            clientId: $this->user()->id,
            filterStatus: $status !== null ? BookingFilterStatus::from($status) : null,
        );
    }
}
