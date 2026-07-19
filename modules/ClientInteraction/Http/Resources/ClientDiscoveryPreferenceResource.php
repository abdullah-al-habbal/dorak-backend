<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ClientDiscoveryPreferenceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'preferred_universe' => $this->preferred_universe,
            'default_radius' => $this->default_radius,
            'hidden_brand_ids' => $this->hidden_brand_ids ?? [],
            'show_unavailable' => $this->show_unavailable,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
