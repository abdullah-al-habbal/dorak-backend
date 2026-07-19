<?php

declare(strict_types=1);

namespace Modules\Client\Http\Requests\Client;

use Illuminate\Support\Facades\Hash;
use Modules\Client\CQRS\Command\Client\ChangePasswordCommand;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class ChangePasswordRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password:client'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function toCommand(): ChangePasswordCommand
    {
        return new ChangePasswordCommand(
            clientId: (string) $this->user()?->id,
            password: Hash::make((string) $this->validated('password')),
        );
    }
}
