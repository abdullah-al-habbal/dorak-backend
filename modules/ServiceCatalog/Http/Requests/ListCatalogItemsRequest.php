<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Http\Requests;

use Modules\Core\Http\Requests\BaseApiFormRequest;
use Modules\ServiceCatalog\CQRS\Query\ListCatalogItemsQuery;

final class ListCatalogItemsRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'category_id' => ['nullable', 'integer', 'exists:service_catalog_categories,id'],
            'search' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function toQuery(): ListCatalogItemsQuery
    {
        return new ListCatalogItemsQuery(
            categoryId: $this->validated('category_id') !== null ? (int) $this->validated('category_id') : null,
            search: $this->validated('search'),
            perPage: (int) ($this->validated('per_page') ?? 20),
        );
    }
}
