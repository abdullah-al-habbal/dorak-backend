<?php

declare(strict_types=1);

namespace Modules\OfferedService\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class ServiceResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
        ];
    }
}
