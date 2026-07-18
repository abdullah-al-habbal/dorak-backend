<?php

declare(strict_types=1);

namespace Modules\Explore\Http\Requests\Shared;

use Modules\Core\Http\Requests\BaseApiFormRequest;
use Modules\Explore\CQRS\Query\Shared\ExploreBarbersQuery;

final class ExploreBarbersRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'radius' => ['nullable', 'numeric', 'min:0'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function toQuery(): ExploreBarbersQuery
    {
        return new ExploreBarbersQuery(
            lat: (float) $this->validated('lat'),
            lng: (float) $this->validated('lng'),
            radius: (float) ($this->validated('radius') ?? 10),
            perPage: (int) ($this->validated('per_page') ?? 20),
        );
    }
}
