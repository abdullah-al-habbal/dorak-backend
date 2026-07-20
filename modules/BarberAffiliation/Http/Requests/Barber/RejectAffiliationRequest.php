<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Http\Requests\Barber;

use Modules\BarberAffiliation\CQRS\Command\Barber\RejectAffiliationCommand;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class RejectAffiliationRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [];
    }

    public function toCommand(string $affiliationId): RejectAffiliationCommand
    {
        return new RejectAffiliationCommand(
            affiliationId: $affiliationId,
        );
    }
}
