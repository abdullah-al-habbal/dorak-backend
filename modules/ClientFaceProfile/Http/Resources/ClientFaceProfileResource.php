<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class ClientFaceProfileResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'image_url' => $this->image_url,
            'is_primary' => $this->is_primary,
            'uploaded_at' => $this->uploaded_at->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
