<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Http\Requests;

use Illuminate\Validation\Rules\Enum;
use Modules\BarberAffiliation\Enums\AffiliableType;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class CreateAffiliationRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'affiliable_id' => ['required', 'string'],
            'affiliable_type' => ['required', 'string', new Enum(AffiliableType::class)],
            'commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }
}
