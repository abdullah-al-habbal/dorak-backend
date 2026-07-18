<?php

declare(strict_types=1);

namespace Modules\Brand\Http\Requests\Client;

use Modules\Brand\CQRS\Command\Client\CreateBrandCommand;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class CreateBrandRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'name.en' => ['required', 'string', 'max:255'],
            'name.ar' => ['required', 'string', 'max:255'],
            'owner_id' => ['required', 'string', 'exists:clients,id'],
            'base_currency_id' => ['required', 'string', 'exists:currencies,id'],
            'logo' => ['nullable', 'url', 'max:2048'],
            'description.en' => ['nullable', 'string', 'max:5000'],
            'description.ar' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function toCommand(): CreateBrandCommand
    {
        return new CreateBrandCommand(
            ownerId: $this->validated('owner_id'),
            nameEn: $this->validated('name.en'),
            nameAr: $this->validated('name.ar'),
            baseCurrencyId: $this->validated('base_currency_id'),
            logo: $this->validated('logo'),
            descriptionEn: $this->validated('description.en'),
            descriptionAr: $this->validated('description.ar'),
        );
    }
}
