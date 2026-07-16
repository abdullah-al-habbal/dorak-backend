<?php

declare(strict_types=1);

namespace Modules\Client\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ChangePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password:client'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }
}
