<?php

declare(strict_types=1);

namespace Modules\Ban\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Client\Models\ClientModel;

final class BanCheckResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'is_banned' => $this->resource->isBanned(),
        ];
    }
}
