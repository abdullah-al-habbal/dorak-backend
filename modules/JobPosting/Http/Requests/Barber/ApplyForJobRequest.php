<?php

declare(strict_types=1);

namespace Modules\JobPosting\Http\Requests\Barber;

use Modules\Core\Http\Requests\BaseApiFormRequest;

final class ApplyForJobRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'cover_letter' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
