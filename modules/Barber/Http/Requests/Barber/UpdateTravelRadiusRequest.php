<?php

declare(strict_types=1);

namespace Modules\Barber\Http\Requests\Barber;

use Modules\Barber\CQRS\Command\Barber\UpdateTravelRadiusCommand;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class UpdateTravelRadiusRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'travel_radius' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }

    public function toCommand(): UpdateTravelRadiusCommand
    {
        return new UpdateTravelRadiusCommand(
            barberId: (string) $this->user()->id,
            travelRadius: $this->validated('travel_radius') !== null ? (float) $this->validated('travel_radius') : null,
            latitude: $this->validated('latitude') !== null ? (float) $this->validated('latitude') : null,
            longitude: $this->validated('longitude') !== null ? (float) $this->validated('longitude') : null,
        );
    }
}
