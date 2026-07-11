<?php
declare(strict_types=1);

namespace Modules\Branch\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class ChairResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'status' => $this->status,
            'ui_metadata' => $this->ui_metadata,
            'barber' => $this->when($this->relationLoaded('barber') && $this->barber !== null, fn () => [
                'id' => $this->barber->id,
                'name' => $this->barber->name,
            ]),
        ];
    }
}
