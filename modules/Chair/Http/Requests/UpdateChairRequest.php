<?php

declare(strict_types=1);

namespace Modules\Chair\Http\Requests;

use Illuminate\Validation\Rules\Enum;
use Modules\Chair\CQRS\Command\UpdateChairCommand;
use Modules\Chair\Enums\ChairShape;
use Modules\Chair\Enums\ChairStatus;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class UpdateChairRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'label' => ['sometimes', 'string', 'max:255'],
            'status' => ['sometimes', 'string', new Enum(ChairStatus::class)],
            'barber_id' => ['nullable', 'string', 'exists:barbers,id'],
            'ui_metadata' => ['nullable', 'array'],
            'ui_metadata.position_x' => ['nullable', 'numeric'],
            'ui_metadata.position_y' => ['nullable', 'numeric'],
            'ui_metadata.width' => ['nullable', 'numeric'],
            'ui_metadata.height' => ['nullable', 'numeric'],
            'ui_metadata.rotation' => ['nullable', 'numeric'],
            'ui_metadata.shape' => ['nullable', 'string', new Enum(ChairShape::class)],
        ];
    }

    public function toCommand(string $chairId): UpdateChairCommand
    {
        return new UpdateChairCommand(
            chairId: $chairId,
            label: $this->validated('label'),
            status: $this->validated('status'),
            barberId: $this->validated('barber_id'),
            uiMetadata: $this->validated('ui_metadata'),
        );
    }
}
