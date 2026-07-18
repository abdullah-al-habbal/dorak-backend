<?php

declare(strict_types=1);

namespace Modules\Explore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ExploreBranchesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'radius' => ['nullable', 'numeric', 'min:0'],
            // todo: use Enum instead of "in"
            'universe' => ['nullable', 'string', 'in:men,women,neutral'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
