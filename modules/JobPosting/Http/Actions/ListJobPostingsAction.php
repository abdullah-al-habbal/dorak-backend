<?php

declare(strict_types=1);

namespace Modules\JobPosting\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\JobPosting\Http\Resources\JobPostingResource;
use Modules\JobPosting\Models\JobPostingModel;

final class ListJobPostingsAction extends BaseApiAction
{
    public function __invoke(): JsonResponse
    {
        $jobs = JobPostingModel::withCount('applications')
            ->where('status', 'open')
            ->orderByDesc('created_at')
            ->paginate(20);

        return $this->paginated(
            paginator: $jobs,
            resourceClass: JobPostingResource::class,
        );
    }
}
