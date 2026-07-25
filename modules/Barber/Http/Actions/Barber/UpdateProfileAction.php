<?php

declare(strict_types=1);

namespace Modules\Barber\Http\Actions\Barber;

use Illuminate\Http\JsonResponse;
use Modules\Barber\Handlers\Barber\UpdateProfileHandler;
use Modules\Barber\Http\Requests\Barber\UpdateProfileRequest;
use Modules\Barber\Http\Resources\BarberResource;
use Modules\Core\Http\Actions\BaseApiAction;

final class UpdateProfileAction extends BaseApiAction
{
    public function __construct(
        private readonly UpdateProfileHandler $handler,
    ) {}

    public function __invoke(UpdateProfileRequest $request): JsonResponse
    {
        $barber = $this->handler->handle($request->toCommand());

        return $this->ok(data: new BarberResource($barber));
    }
}
