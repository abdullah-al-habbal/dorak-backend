<?php

declare(strict_types=1);

namespace Modules\Client\Http\Requests\Client;

use Modules\Core\Http\Requests\BaseApiFormRequest;

final class SocialLoginRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'access_token' => ['required', 'string'],
        ];
    }
}
