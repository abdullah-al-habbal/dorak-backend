<?php

declare(strict_types=1);

namespace Modules\Brand\Http\Requests\Client;

use Modules\Brand\CQRS\Command\Client\UpdateBrandCommand;
use Modules\Brand\Models\BrandModel;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class UpdateBrandRequest extends BaseApiFormRequest
{
    // todo: is better to make a middleware to resolve the current use and validate it.
    public function authorize(): bool
    {
        $brandId = $this->route('brand');
        $brand = BrandModel::findOrFail($brandId);

        return $brand->owner_id === $this->user()->getAuthIdentifier();
    }

    public function rules(): array
    {
        return [
            'name.en' => ['sometimes', 'string', 'max:255'],
            'name.ar' => ['sometimes', 'string', 'max:255'],
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
            ownerId: null,
            baseCurrencyId: $data['base_currency_id'] ?? null,
            logo: $data['logo'] ?? null,
            descriptionEn: $data['description']['en'] ?? null,
            descriptionAr: $data['description']['ar'] ?? null,
        );
    }
}
