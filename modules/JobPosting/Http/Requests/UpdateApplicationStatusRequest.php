<?php

declare(strict_types=1);

namespace Modules\JobPosting\Http\Requests;

use Modules\Core\Http\Requests\BaseApiFormRequest;

final class UpdateApplicationStatusRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', 'in:submitted,reviewed,accepted,rejected'],
        ];
    }
}
