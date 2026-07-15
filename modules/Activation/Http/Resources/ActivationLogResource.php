<?php

declare(strict_types=1);

namespace Modules\Activation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class ActivationLogResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'activable_id' => $this->activable_id,
            'activable_type' => $this->activable_type,
            'status' => $this->status,
            'reason' => $this->reason,
            'activated_at' => $this->activated_at?->toIso8601String(),
            'expires_at' => $this->expires_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
