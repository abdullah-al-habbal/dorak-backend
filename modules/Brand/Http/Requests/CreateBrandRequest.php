<?php

declare(strict_types=1);

namespace Modules\Brand\Http\Requests;

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
}
