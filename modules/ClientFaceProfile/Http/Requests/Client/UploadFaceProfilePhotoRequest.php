<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\Http\Requests\Client;

use Modules\Core\Http\Requests\BaseApiFormRequest;

final class UploadFaceProfilePhotoRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
            'is_primary' => ['sometimes', 'boolean'],
        ];
    }
}
