<?php

declare(strict_types=1);

namespace Modules\Explore\Http\Requests\Shared;

use Modules\Core\Http\Requests\BaseApiFormRequest;
use Modules\Explore\CQRS\Query\Shared\ExploreBranchesQuery;

final class ExploreBranchesRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'radius' => ['nullable', 'numeric', 'min:0'],
            // todo: use Enum instead of "in"
            'universe' => ['nullable', 'string', 'in:men,women,neutral'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function toQuery(): ExploreBranchesQuery
    {
        return new ExploreBranchesQuery(
            lat: (float) $this->validated('lat'),
            lng: (float) $this->validated('lng'),
            radius: (float) ($this->validated('radius') ?? 10),
            universe: $this->validated('universe'),
            perPage: (int) ($this->validated('per_page') ?? 20),
        );
    }
}
