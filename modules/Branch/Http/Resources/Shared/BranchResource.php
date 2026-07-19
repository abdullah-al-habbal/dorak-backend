<?php

declare(strict_types=1);

namespace Modules\Branch\Http\Resources\Shared;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class BranchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'brand_id' => $this->brand_id,
            'distance' => $this->distance ?? null,
            'created_at' => $this->created_at,
        ];
    }
}
