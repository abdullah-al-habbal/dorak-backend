<?php

declare(strict_types=1);

namespace Modules\ClientHistory\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class ClientServiceHistoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'booking_id' => $this->booking_id,
            'barber_id' => $this->barber_id,
            'branch_id' => $this->branch_id,
            'offered_service_id' => $this->offered_service_id,
            'catalog_item_id' => $this->catalog_item_id,
            'performed_at' => $this->performed_at?->toIso8601String(),
            'client_rating' => $this->client_rating,
            'client_notes' => $this->client_notes,
            'barber_notes' => $this->barber_notes,
            'metadata' => $this->metadata?->toArray(),
            'barber' => $this->whenLoaded('barber', fn () => [
                'id' => $this->barber->id,
                'name' => $this->barber->name,
            ]),
            'branch' => $this->whenLoaded('branch', fn () => [
                'id' => $this->branch->id,
                'name' => $this->branch->name,
            ]),
            'catalog_item' => $this->whenLoaded('catalogItem', fn () => [
                'id' => $this->catalogItem->id,
                'name' => $this->catalogItem->getTranslations('name'),
            ]),
            'media' => $this->whenLoaded('media', fn () => $this->media->map(fn ($m) => [
                'id' => $m->id,
                'photo_url' => $m->photo_url,
                'photo_type' => $m->photo_type->value,
                'uploaded_at' => $m->uploaded_at->toIso8601String(),
            ])),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
