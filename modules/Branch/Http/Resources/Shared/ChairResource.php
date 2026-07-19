<?php

declare(strict_types=1);

namespace Modules\Branch\Http\Resources\Shared;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Barber\Http\Resources\BarberResource;

final class ChairResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'status' => $this->status,
            'ui_metadata' => $this->ui_metadata,
            'barber' => BarberResource::make($this->whenLoaded('barber')),
        ];
    }
}
