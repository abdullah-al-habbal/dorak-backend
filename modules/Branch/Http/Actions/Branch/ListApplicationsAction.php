<?php

declare(strict_types=1);

namespace Modules\Branch\Http\Actions\Branch;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Core\Enums\SuccessCodeEnum;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\JobPosting\Models\ApplicationModel;
use Modules\JobPosting\Models\JobPostingModel;

final class ListApplicationsAction extends BaseApiAction
{
    public function __invoke(Request $request, string $jobPosting): JsonResponse
    {
        $branch = $request->user('branch_api');

        JobPostingModel::where('branch_id', $branch->id)->findOrFail($jobPosting);

        $applications = ApplicationModel::where('job_posting_id', $jobPosting)
            ->with('barber')
            ->orderByDesc('created_at')
            ->paginate($request->input('per_page', 15));

        return $this->paginated($applications, \Modules\JobPosting\Http\Resources\Barber\ApplicationResource::class);
    }
}
