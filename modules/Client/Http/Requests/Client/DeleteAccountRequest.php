<?php

declare(strict_types=1);

namespace Modules\Client\Http\Requests\Client;

use Modules\Core\Http\Requests\BaseApiFormRequest;

final class DeleteAccountRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'password' => ['required', 'string'],
        ];
    }
}
