<?php

declare(strict_types=1);

namespace Modules\Client\Http\Requests\Client;

use Modules\Core\Http\Requests\BaseApiFormRequest;

final class ChangePasswordRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password:client'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }
}
