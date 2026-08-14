<?php

declare(strict_types=1);

namespace Modules\Client\Http\Requests\Client;

use Modules\Client\CQRS\Command\Client\RegisterCommand;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class RegisterRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:clients,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function toCommand(): RegisterCommand
    {
        return new RegisterCommand(
            name: $this->validated('name'),
            email: $this->validated('email'),
            password: $this->validated('password'),
            phone: $this->validated('phone'),
        );
    }
}
