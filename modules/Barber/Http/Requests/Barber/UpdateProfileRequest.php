<?php

declare(strict_types=1);

namespace Modules\Barber\Http\Requests\Barber;

use Illuminate\Validation\Rule;
use Modules\Barber\CQRS\Command\Barber\UpdateProfileCommand;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class UpdateProfileRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'array'],
            'email' => ['nullable', 'email', Rule::unique('barbers', 'email')->ignore($this->user()->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_freelancer' => ['nullable', 'boolean'],
        ];
    }

    public function toCommand(): UpdateProfileCommand
    {
        return new UpdateProfileCommand(
            barberId: (string) $this->user()->id,
            name: $this->validated('name'),
            email: $this->validated('email'),
            password: $this->validated('password'),
            isFreelancer: $this->validated('is_freelancer'),
        );
    }
}
