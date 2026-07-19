<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Http\Resources\Shared;

use Illuminate\Http\Resources\Json\JsonResource;

final class ServiceCatalogCategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->getTranslations('name'),
            'description' => $this->getTranslations('description'),
            'slug' => $this->slug,
            'parent_id' => $this->parent_id,
            'children' => self::collection($this->whenLoaded('children')),
            'items_count' => $this->whenLoaded('items', fn () => $this->items->count()),
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
