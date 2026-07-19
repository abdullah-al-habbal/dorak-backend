<?php

declare(strict_types=1);

namespace Modules\ClientHistory\Http\Requests\Client;

use Modules\Core\Http\Requests\BaseApiFormRequest;

final class RebookFromHistoryRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'time_slot' => ['required', 'date', 'after:now'],
        ];
    }
}
