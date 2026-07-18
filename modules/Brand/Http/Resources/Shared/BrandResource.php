<?php

declare(strict_types=1);

namespace Modules\Brand\Http\Resources\Shared;

use Illuminate\Http\Resources\Json\JsonResource;

final class BrandResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->getTranslations('name'),
            'description' => $this->getTranslations('description'),
            'logo' => $this->logo,
            'owner' => $this->whenLoaded('owner', fn () => [
                'id' => $this->owner->id,
                'email' => $this->owner->email,
            ]),
            'base_currency' => $this->whenLoaded('baseCurrency', fn () => [
                'id' => $this->baseCurrency->id,
                'code' => $this->baseCurrency->code,
            ]),
            'branches_count' => $this->whenLoaded('branches', fn () => $this->branches->count()),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
