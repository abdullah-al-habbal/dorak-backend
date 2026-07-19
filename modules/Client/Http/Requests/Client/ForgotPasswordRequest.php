<?php

declare(strict_types=1);

namespace Modules\Client\Http\Requests\Client;

use Modules\Client\CQRS\Command\Client\ForgotPasswordCommand;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class ForgotPasswordRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:clients,email'],
        ];
    }

    public function toCommand(): ForgotPasswordCommand
    {
        return new ForgotPasswordCommand(
            email: (string) $this->validated('email'),
        );
    }
}
