<?php

declare(strict_types=1);

namespace Modules\Activation\Http\Requests;

use Modules\Core\Http\Requests\BaseApiFormRequest;

final class ToggleActivationRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'reason' => ['nullable', 'string', 'max:500'],
        ];
    }
}
