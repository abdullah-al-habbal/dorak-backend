<?php

declare(strict_types=1);

namespace Modules\Booking\Http\Resources\Client;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Barber\Http\Resources\BarberResource;
use Modules\Branch\Http\Resources\Shared\ChairResource;
use Modules\OfferedService\Http\Resources\Shared\ServiceResource;

final class BookingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'time_slot' => $this->time_slot->toIso8601String(),
            'status' => $this->status,
            'chair' => ChairResource::make($this->whenLoaded('chair')),
            'barber' => BarberResource::make($this->whenLoaded('barber')),
            'services' => ServiceResource::collection($this->whenLoaded('services')),
            'at_home_location' => $this->at_home_location,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
