<?php

declare(strict_types=1);

namespace Modules\Explore\Http\Requests\Shared;

use Modules\Core\Http\Requests\BaseApiFormRequest;
use Modules\Explore\CQRS\Query\Shared\GetBarberDetailQuery;

final class GetBarberDetailRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [];
    }

    public function toQuery(string $barberId): GetBarberDetailQuery
    {
        return new GetBarberDetailQuery(
            barberId: $barberId,
        );
    }
}
