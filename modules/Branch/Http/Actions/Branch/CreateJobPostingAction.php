<?php

declare(strict_types=1);

namespace Modules\Branch\Http\Actions\Branch;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Core\Enums\SuccessCodeEnum;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\JobPosting\Enums\JobPostingStatusEnum;
use Modules\JobPosting\Models\JobPostingModel;

final class CreateJobPostingAction extends BaseApiAction
{
    public function __invoke(Request $request): JsonResponse
    {
        $branch = $request->user('branch_api');

        $validated = $request->validate([
            'title' => ['required', 'array'],
            'title.en' => ['required', 'string'],
            'title.ar' => ['required', 'string'],
            'description' => ['required', 'array'],
            'description.en' => ['required', 'string'],
            'description.ar' => ['required', 'string'],
            'requirements' => ['nullable', 'array'],
            'location' => ['nullable', 'string'],
            'type' => ['nullable', 'string'],
        ]);

        $jobPosting = JobPostingModel::create([
            'branch_id' => $branch->id,
            ...$validated,
            'status' => JobPostingStatusEnum::Open,
        ]);

        return $this->success(
            data: $jobPosting->loadCount('applications'),
            code: SuccessCodeEnum::CREATED,
        );
    }
}
