<?php

declare(strict_types=1);

namespace Modules\Booking\Http\Requests\Client;

use Illuminate\Validation\Rule;
use Modules\Booking\CQRS\Query\Client\ListUserBookingsQuery;
use Modules\Booking\Enums\BookingFilterStatus;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class ListUserBookingsRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'status' => ['nullable', Rule::enum(BookingFilterStatus::class)],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function toQuery(): ListUserBookingsQuery
    {
        $statusString = $this->validated('status');

        return new ListUserBookingsQuery(
            clientId: (string) $this->user()->id,
            filterStatus: $statusString !== null ? BookingFilterStatus::from($statusString) : null,
            perPage: (int) ($this->validated('per_page') ?? 20),
        );
    }
}
