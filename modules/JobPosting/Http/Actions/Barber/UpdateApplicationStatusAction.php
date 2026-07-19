<?php

declare(strict_types=1);

namespace Modules\JobPosting\Http\Actions\Barber;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\JobPosting\Http\Requests\Barber\UpdateApplicationStatusRequest;
use Modules\JobPosting\Http\Resources\Barber\ApplicationResource;
use Modules\JobPosting\Models\ApplicationModel;

final class UpdateApplicationStatusAction extends BaseApiAction
{
    public function __invoke(UpdateApplicationStatusRequest $request, string $application): JsonResponse
    {
        $application = ApplicationModel::findOrFail($application);

        // todo: we must have the command/Query based on the api and also the resolver and the hanlder
        $application->update([
            'status' => $request->validated('status'),
        ]);

        return $this->updated(
            data: new ApplicationResource($application),
            message: 'Application status updated',
        );
    }
}
