<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Http\Actions\Barber;

use Illuminate\Http\JsonResponse;
use Modules\BarberAffiliation\Handlers\Barber\CreateAffiliationHandler;
use Modules\BarberAffiliation\Http\Requests\Barber\CreateAffiliationRequest;
use Modules\BarberAffiliation\Http\Resources\Barber\BarberAffiliationResource;
use Modules\BarberAffiliation\Eloquent\Resolvers\Barber\BarberAlreadyAffiliatedException;
use Modules\Core\Enums\ErrorCodeEnum;
use Modules\Core\Http\Actions\BaseApiAction;

final class CreateAffiliationAction extends BaseApiAction
{
    public function __construct(
        private readonly CreateAffiliationHandler $handler,
    ) {}

    public function __invoke(CreateAffiliationRequest $request, string $barber): JsonResponse
    {
        try {
            $result = $this->handler->handle(
                $request->toCommand($barber),
            );
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
