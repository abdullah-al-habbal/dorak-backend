<?php

declare(strict_types=1);

namespace Modules\JobPosting\Http\Actions\Shared;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\JobPosting\Http\Resources\Shared\JobPostingResource;
use Modules\JobPosting\Models\JobPostingModel;

final class ShowJobPostingAction extends BaseApiAction
{
    public function __invoke(string $job): JsonResponse
    {
        $job = JobPostingModel::with('branch')->findOrFail($job);

        return $this->ok(data: new JobPostingResource($job));
    }
}
