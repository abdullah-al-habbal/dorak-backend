<?php

declare(strict_types=1);

namespace Modules\Ban\Http\Requests\Client;

use Modules\Ban\CQRS\Query\Client\CheckClientBanQuery;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class CheckClientBanRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [];
    }

    public function toQuery(string $clientId): CheckClientBanQuery
    {
        return new CheckClientBanQuery(
            clientId: $clientId,
        );
    }
}
