<?php

declare(strict_types=1);

namespace Modules\Review\Http\Resources\Shared;

use Illuminate\Http\Resources\Json\JsonResource;

final class ReviewResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'author_name' => $this->whenLoaded('author', fn () => $this->author->name),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
