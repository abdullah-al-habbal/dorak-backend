<?php

declare(strict_types=1);

namespace Modules\Client\Http\Requests\Client;

use Modules\Client\CQRS\Command\Client\LoginCommand;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class LoginRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function toCommand(): LoginCommand
    {
        return new LoginCommand(
            email: (string) $this->validated('email'),
            password: (string) $this->validated('password'),
        );
    }
}
