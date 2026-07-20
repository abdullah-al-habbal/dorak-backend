<?php

declare(strict_types=1);

namespace Modules\ClientHistory\Http\Requests\Client;

use Modules\ClientHistory\CQRS\Query\ListClientServiceHistoryQuery;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class ListClientServiceHistoryRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
            'catalog_item_id' => ['nullable', 'string', 'exists:service_catalog_items,id'],
        ];
    }

    public function toQuery(): ListClientServiceHistoryQuery
    {
        return new ListClientServiceHistoryQuery(
            clientId: (string) $this->user()->id,
            perPage: (int) $this->validated('per_page', 15),
            catalogItemId: $this->validated('catalog_item_id'),
        );
    }
}
