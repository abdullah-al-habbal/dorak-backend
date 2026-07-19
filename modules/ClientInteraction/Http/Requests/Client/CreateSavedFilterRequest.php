<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Http\Requests\Client;

use Modules\ClientInteraction\CQRS\Command\CreateSavedFilterCommand;
use Modules\ClientInteraction\ValuesObjects\FilterConfigurationValueObject;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class CreateSavedFilterRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'filter_config' => ['required', 'array'],
        ];
    }

    public function toCommand(): CreateSavedFilterCommand
    {
        return new CreateSavedFilterCommand(
            clientId: (string) $this->user()->id,
            name: $this->validated('name'),
            filterConfig: FilterConfigurationValueObject::fromArray($this->validated('filter_config')),
        );
    }
}
