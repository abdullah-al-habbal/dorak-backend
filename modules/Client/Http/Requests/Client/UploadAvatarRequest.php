<?php

declare(strict_types=1);

namespace Modules\Client\Http\Requests\Client;

use Modules\Core\Http\Requests\BaseApiFormRequest;

final class UploadAvatarRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }
}
