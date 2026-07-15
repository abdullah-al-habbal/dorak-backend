<?php

declare(strict_types=1);

namespace Modules\Chair\Http\Requests;

use Modules\Core\Http\Requests\BaseApiFormRequest;

final class UpdateChairRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'label' => ['sometimes', 'string', 'max:255'],
            'status' => ['sometimes', 'string', 'in:available,occupied,maintenance'],
            'barber_id' => ['nullable', 'string', 'exists:barbers,id'],
            'ui_metadata' => ['nullable', 'array'],
            'ui_metadata.position_x' => ['nullable', 'numeric'],
            'ui_metadata.position_y' => ['nullable', 'numeric'],
            'ui_metadata.width' => ['nullable', 'numeric'],
            'ui_metadata.height' => ['nullable', 'numeric'],
            'ui_metadata.rotation' => ['nullable', 'numeric'],
            'ui_metadata.shape' => ['nullable', 'string', 'in:rectangle,circle,ellipse'],
        ];
    }
}
