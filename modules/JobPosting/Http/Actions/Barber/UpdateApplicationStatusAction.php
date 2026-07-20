<?php

declare(strict_types=1);

namespace Modules\JobPosting\Http\Actions\Barber;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\JobPosting\Handlers\Barber\UpdateApplicationStatusHandler;
use Modules\JobPosting\Http\Requests\Barber\UpdateApplicationStatusRequest;
use Modules\JobPosting\Http\Resources\Barber\ApplicationResource;

final class UpdateApplicationStatusAction extends BaseApiAction
{
    public function __construct(
        private readonly UpdateApplicationStatusHandler $handler,
    ) {}

    public function __invoke(UpdateApplicationStatusRequest $request, string $application): JsonResponse
    {
        $applicationModel = $this->handler->handle($request->toCommand($application));

        return $this->updated(
            data: new ApplicationResource($applicationModel),
            message: 'Application status updated',
        );
    }
}
