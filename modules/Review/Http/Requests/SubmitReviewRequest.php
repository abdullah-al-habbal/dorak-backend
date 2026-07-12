<?php

declare(strict_types=1);

namespace Modules\Review\Http\Requests;

use Modules\Core\Http\Requests\BaseApiFormRequest;

final class SubmitReviewRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
