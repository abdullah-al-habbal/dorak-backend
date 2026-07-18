<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Http\Resources\Barber;

use Illuminate\Http\Resources\Json\JsonResource;

final class BarberAffiliationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'barber_id' => $this->barber_id,
            'affiliable_id' => $this->affiliable_id,
            'affiliable_type' => $this->affiliable_type,
            'status' => $this->status,
            'commission_rate' => $this->commission_rate,
            'invited_at' => $this->invited_at?->toIso8601String(),
            'accepted_at' => $this->accepted_at?->toIso8601String(),
            'terminated_at' => $this->terminated_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
