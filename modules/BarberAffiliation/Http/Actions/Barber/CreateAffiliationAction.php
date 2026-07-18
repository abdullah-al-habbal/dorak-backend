<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Http\Actions\Barber;

use Illuminate\Http\JsonResponse;
use Modules\BarberAffiliation\CQRS\Command\Barber\CreateAffiliationCommand;
use Modules\BarberAffiliation\Eloquent\Resolvers\Barber\BarberAlreadyAffiliatedException;
use Modules\BarberAffiliation\Handlers\Barber\CreateAffiliationHandler;
use Modules\BarberAffiliation\Http\Requests\Barber\CreateAffiliationRequest;
use Modules\BarberAffiliation\Http\Resources\Barber\BarberAffiliationResource;
use Modules\Core\Enums\ErrorCodeEnum;
use Modules\Core\Http\Actions\BaseApiAction;

final class CreateAffiliationAction extends BaseApiAction
{
    public function __construct(
        private readonly CreateAffiliationHandler $handler,
    ) {}

    public function __invoke(CreateAffiliationRequest $request, string $barber): JsonResponse
    {
        $command = new CreateAffiliationCommand(
            barberId: $barber,
            affiliableId: $request->validated('affiliable_id'),
            affiliableType: $request->validated('affiliable_type'),
            commissionRate: $request->validated('commission_rate'),
        );

        try {
            $result = $this->handler->handle($command);
        } catch (BarberAlreadyAffiliatedException) {
            return $this->businessError(
                ErrorCodeEnum::CONFLICT,
                message: 'Barber already affiliated to a branch',
            );
        }

        return $this->created(
            data: new BarberAffiliationResource($result->affiliation),
            message: 'Affiliation created successfully',
        );
    }
}
