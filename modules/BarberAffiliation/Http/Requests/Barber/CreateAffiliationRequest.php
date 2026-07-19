<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Http\Requests\Barber;

use Illuminate\Validation\Rule;
use Modules\BarberAffiliation\CQRS\Command\Barber\CreateAffiliationCommand;
use Modules\BarberAffiliation\Enums\AffiliableType;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class CreateAffiliationRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'affiliable_id' => ['required', 'string'],
            'affiliable_type' => ['required', Rule::enum(AffiliableType::class)],
            'commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }

    public function toCommand(string $barber): CreateAffiliationCommand
    {
        return new CreateAffiliationCommand(
            barberId: $barber,
            affiliableId: (string) $this->validated('affiliable_id'),
            affiliableType: AffiliableType::from((string) $this->validated('affiliable_type')),
            commissionRate: $this->validated('commission_rate') !== null ? (float) $this->validated('commission_rate') : null,
        );
    }
}
