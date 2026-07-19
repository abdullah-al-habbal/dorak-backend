<?php

declare(strict_types=1);

namespace Modules\Client\Http\Requests\Client;

use Modules\Client\CQRS\Command\Client\DeleteAccountCommand;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class DeleteAccountRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'password' => ['required', 'string'],
        ];
    }

    public function toCommand(): DeleteAccountCommand
    {
        return new DeleteAccountCommand(
            clientId: (string) $this->user()?->id,
            password: (string) $this->validated('password'),
        );
    }
}
