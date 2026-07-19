<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ClientFavoriteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'favorable_id' => $this->favorable_id,
            'favorable_type' => $this->favorable_type,
            'favorable' => $this->whenLoaded('favorable', fn () => $this->favorable),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
