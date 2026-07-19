<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Http\Resources\Shared;

use Illuminate\Http\Resources\Json\JsonResource;

final class ServiceCatalogItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'name' => $this->getTranslations('name'),
            'description' => $this->getTranslations('description'),
            'slug' => $this->slug,
            'sku' => $this->sku,
            'price_range' => $this->price_range?->toArray(),
            'maintenance_level' => $this->maintenance_level,
            'style_period' => $this->style_period,
            'formality' => $this->formality,
            'face_shapes' => $this->face_shapes,
            'hair_textures' => $this->hair_textures,
            'metadata' => $this->metadata?->toArray(),
            'category' => $this->whenLoaded('category', fn () => [
                'id' => $this->category->id,
                'name' => $this->category->getTranslations('name'),
                'slug' => $this->category->slug,
            ]),
            'tags' => $this->whenLoaded('tags', fn () => $this->tags->map(fn ($tag) => [
                'id' => $tag->id,
                'name' => $tag->getTranslations('name'),
                'slug' => $tag->slug,
                'group' => $tag->group,
            ])),
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
