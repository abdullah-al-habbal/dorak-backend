<?php

declare(strict_types=1);

namespace Modules\OfferedService\Http\Requests\Shared;

use Modules\Core\Http\Requests\BaseApiFormRequest;
use Modules\OfferedService\CQRS\Query\Shared\ListBarberServicesQuery;

final class ListBarberServicesRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [];
    }

    public function toQuery(string $barberId): ListBarberServicesQuery
    {
        return new ListBarberServicesQuery(
            barberId: $barberId,
        );
    }
}
