<?php
declare(strict_types=1);

namespace Modules\Booking\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class BookingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'time_slot' => $this->time_slot->toIso8601String(),
            'status' => $this->status,
            'chair' => $this->when($this->relationLoaded('chair') && $this->chair !== null, fn () => [
                'id' => $this->chair->id,
                'label' => $this->chair->label,
                'status' => $this->chair->status,
            ]),
            'barber' => $this->when($this->relationLoaded('barber') && $this->barber !== null, fn () => [
                'id' => $this->barber->id,
                'name' => $this->barber->name,
            ]),
            'services' => $this->when($this->relationLoaded('services'), fn () =>
                $this->services->map(fn ($service) => [
                    'id' => $service->id,
                    'name' => $service->name,
                    'price' => $service->price,
                ]),
            ),
            'at_home_location' => $this->at_home_location,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
