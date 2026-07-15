<?php

declare(strict_types=1);

namespace Modules\Currency\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class CurrencyResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->getTranslations('name'),
            'symbol' => $this->symbol,
            'is_default' => $this->is_default,
        ];
    }
}
