<?php

declare(strict_types=1);

namespace Modules\ClientHistory\Http\Requests\Client;

use Illuminate\Validation\Rule;
use Modules\ClientHistory\Enums\HistoryMediaType;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class AttachHistoryMediaRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'photo_url' => ['required', 'string', 'max:2048'],
            'photo_type' => ['required', Rule::enum(HistoryMediaType::class)],
        ];
    }
}
