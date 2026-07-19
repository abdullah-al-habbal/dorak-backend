<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Http\Requests\Client;

use Modules\ClientInteraction\CQRS\Command\UpdateSavedFilterCommand;
use Modules\ClientInteraction\ValuesObjects\FilterConfigurationValueObject;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class UpdateSavedFilterRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'filter_config' => ['required', 'array'],
        ];
    }

    public function toCommand(string $filterId): UpdateSavedFilterCommand
    {
        return new UpdateSavedFilterCommand(
            filterId: $filterId,
            clientId: (string) $this->user()->id,
            name: $this->validated('name'),
            filterConfig: FilterConfigurationValueObject::fromArray($this->validated('filter_config')),
        );
    }
}
