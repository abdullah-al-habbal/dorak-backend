<?php

declare(strict_types=1);

namespace Modules\Client\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateUniversePreferenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // todo: use Enum instead of "in"
            'universe' => ['required', 'string', 'in:men,women,neutral'],
        ];
    }
}
