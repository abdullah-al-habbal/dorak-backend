<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Http\Actions\Barber;

use Illuminate\Http\JsonResponse;
use Modules\BarberAffiliation\Handlers\Barber\AcceptAffiliationHandler;
use Modules\BarberAffiliation\Http\Requests\Barber\AcceptAffiliationRequest;
use Modules\BarberAffiliation\Http\Resources\Barber\BarberAffiliationResource;
use Modules\Core\Enums\ErrorCodeEnum;
use Modules\Core\Http\Actions\BaseApiAction;

final class AcceptAffiliationAction extends BaseApiAction
{
    public function __construct(
        private readonly AcceptAffiliationHandler $handler,
    ) {}

    public function __invoke(AcceptAffiliationRequest $request, string $affiliation): JsonResponse
    {
        $result = $this->handler->handle($request->toCommand($affiliation));

        if (! $result->success) {
            return $this->businessError(ErrorCodeEnum::BAD_REQUEST, message: 'Affiliation is not in pending status');
        }

        return $this->ok(
            data: new BarberAffiliationResource($result->affiliation),
            message: 'Affiliation accepted successfully',
        );
    }
}
