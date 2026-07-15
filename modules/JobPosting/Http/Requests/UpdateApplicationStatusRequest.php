<?php

declare(strict_types=1);

namespace Modules\JobPosting\Http\Requests;

use Illuminate\Validation\Rules\Enum;
use Modules\Core\Http\Requests\BaseApiFormRequest;
use Modules\JobPosting\Enums\ApplicationStatus;

final class UpdateApplicationStatusRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', new Enum(ApplicationStatus::class)],
        ];
    }
}
