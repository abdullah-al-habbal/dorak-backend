<?php

declare(strict_types=1);

namespace Modules\Client\Http\Requests\Client;

use Illuminate\Validation\Rule;
use Modules\Client\CQRS\Command\Client\UpdateUniversePreferenceCommand;
use Modules\Client\Enums\UniverseEnum;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class UpdateUniversePreferenceRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'universe' => ['required', Rule::enum(UniverseEnum::class)],
        ];
    }

    public function toCommand(): UpdateUniversePreferenceCommand
    {
        return new UpdateUniversePreferenceCommand(
            clientId: (string) $this->user()->id,
            universe: UniverseEnum::from($this->validated('universe')),
        );
    }
}
