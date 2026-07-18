<?php

declare(strict_types=1);

namespace Modules\Brand\Http\Requests;

use Modules\Brand\CQRS\Command\UpdateBrandCommand;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class UpdateBrandRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'name.en' => ['sometimes', 'string', 'max:255'],
            'name.ar' => ['sometimes', 'string', 'max:255'],
            'owner_id' => ['sometimes', 'string', 'exists:clients,id'],
            'base_currency_id' => ['sometimes', 'string', 'exists:currencies,id'],
            'logo' => ['nullable', 'url', 'max:2048'],
            'description.en' => ['nullable', 'string', 'max:5000'],
            'description.ar' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function toCommand(string $brandId): UpdateBrandCommand
    {
        $data = $this->validated();

        return new UpdateBrandCommand(
            brandId: $brandId,
            nameEn: $data['name']['en'] ?? null,
            nameAr: $data['name']['ar'] ?? null,
            ownerId: $data['owner_id'] ?? null,
            baseCurrencyId: $data['base_currency_id'] ?? null,
            logo: $data['logo'] ?? null,
            descriptionEn: $data['description']['en'] ?? null,
            descriptionAr: $data['description']['ar'] ?? null,
        );
    }
}
