<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Http\Requests\Client;

use Illuminate\Validation\Rule;
use Modules\ClientInteraction\CQRS\Query\ListFavoritesQuery;
use Modules\ClientInteraction\Enums\FavorableTypeEnum;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class ListFavoritesRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'favorable_type' => ['nullable', Rule::enum(FavorableTypeEnum::class)],
            'per_page' => ['required', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function toQuery(): ListFavoritesQuery
    {
        $typeString = $this->validated('favorable_type');

        return new ListFavoritesQuery(
            clientId: (string) $this->user()->id,
            favorableType: $typeString !== null ? FavorableTypeEnum::from($typeString) : null,
            perPage: (int) $this->validated('per_page'),
        );
    }
}
