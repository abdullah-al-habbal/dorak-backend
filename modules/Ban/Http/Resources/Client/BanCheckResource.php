<?php

declare(strict_types=1);

namespace Modules\Ban\Http\Resources\Client;

use Illuminate\Http\Resources\Json\JsonResource;

final class BanCheckResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'is_banned' => $this->resource->isBanned(),
        ];
    }
}
