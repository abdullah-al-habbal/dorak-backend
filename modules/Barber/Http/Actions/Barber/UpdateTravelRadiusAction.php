<?php

declare(strict_types=1);

namespace Modules\Barber\Http\Actions\Barber;

use Illuminate\Http\JsonResponse;
use Modules\Barber\Handlers\Barber\UpdateTravelRadiusHandler;
use Modules\Barber\Http\Requests\Barber\UpdateTravelRadiusRequest;
use Modules\Barber\Http\Resources\BarberResource;
use Modules\Core\Http\Actions\BaseApiAction;

final class UpdateTravelRadiusAction extends BaseApiAction
{
    public function __construct(
        private readonly UpdateTravelRadiusHandler $handler,
    ) {}

    public function __invoke(UpdateTravelRadiusRequest $request): JsonResponse
    {
        $barber = $this->handler->handle($request->toCommand());

        return $this->ok(data: new BarberResource($barber));
    }
}
