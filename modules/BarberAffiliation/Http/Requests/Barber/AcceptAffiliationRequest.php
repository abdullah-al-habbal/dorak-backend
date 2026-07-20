<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Http\Requests\Barber;

use Modules\BarberAffiliation\CQRS\Command\Barber\AcceptAffiliationCommand;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class AcceptAffiliationRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [];
    }

    public function toCommand(string $affiliationId): AcceptAffiliationCommand
    {
        return new AcceptAffiliationCommand(
            affiliationId: $affiliationId,
        );
    }
}
