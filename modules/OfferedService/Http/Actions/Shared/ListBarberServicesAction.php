<?php

declare(strict_types=1);

namespace Modules\OfferedService\Http\Actions\Shared;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\OfferedService\Handlers\Shared\ListBarberServicesHandler;
use Modules\OfferedService\Http\Requests\Shared\ListBarberServicesRequest;
use Modules\OfferedService\Http\Resources\Shared\ServiceResource;

final class ListBarberServicesAction extends BaseApiAction
{
    public function __construct(
        private readonly ListBarberServicesHandler $handler,
    ) {}

    public function __invoke(ListBarberServicesRequest $request, string $barber): JsonResponse
    {
        $services = $this->handler->handle($request->toQuery($barber));

        return $this->ok(
            data: ServiceResource::collection($services),
        );
    }
}
