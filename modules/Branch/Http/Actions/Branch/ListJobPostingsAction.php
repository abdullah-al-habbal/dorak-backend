<?php

declare(strict_types=1);

namespace Modules\Branch\Http\Actions\Branch;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\JobPosting\Http\Resources\Shared\JobPostingResource;
use Modules\JobPosting\Models\JobPostingModel;

final class ListJobPostingsAction extends BaseApiAction
{
    public function __invoke(Request $request): JsonResponse
    {
        $branch = $request->user('branch_api');

        $jobPostings = JobPostingModel::where('branch_id', $branch->id)
            ->withCount('applications')
            ->orderByDesc('created_at')
            ->paginate($request->input('per_page', 15));

        return $this->paginated($jobPostings, JobPostingResource::class);
    }
}
