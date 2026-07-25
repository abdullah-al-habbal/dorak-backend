<?php

declare(strict_types=1);

namespace Modules\Currency\Http\Requests\Admin;

use Modules\Core\Http\Requests\BaseApiFormRequest;
use Modules\Currency\CQRS\Query\Shared\ConvertCurrencyQuery;

final class ConvertCurrencyRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'from' => ['required', 'string', 'size:3'],
            'to' => ['required', 'string', 'size:3'],
            'amount' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function toQuery(): ConvertCurrencyQuery
    {
        return new ConvertCurrencyQuery(
            from: $this->validated('from'),
            to: $this->validated('to'),
            amount: (float) $this->validated('amount', 1),
        );
    }
}
