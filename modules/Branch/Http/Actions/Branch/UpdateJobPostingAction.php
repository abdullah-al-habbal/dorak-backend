<?php

declare(strict_types=1);

namespace Modules\Branch\Http\Actions\Branch;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Core\Enums\SuccessCodeEnum;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\JobPosting\Models\JobPostingModel;

final class UpdateJobPostingAction extends BaseApiAction
{
    public function __invoke(Request $request, string $jobPosting): JsonResponse
    {
        $branch = $request->user('branch_api');

        $jobPostingModel = JobPostingModel::where('branch_id', $branch->id)->findOrFail($jobPosting);

        $validated = $request->validate([
            'title' => ['sometimes', 'array'],
            'title.en' => ['required_with:title', 'string'],
            'title.ar' => ['required_with:title', 'string'],
            'description' => ['sometimes', 'array'],
            'description.en' => ['required_with:description', 'string'],
            'description.ar' => ['required_with:description', 'string'],
            'requirements' => ['nullable', 'array'],
            'location' => ['nullable', 'string'],
            'type' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'in:open,closed,archived'],
        ]);

        $jobPostingModel->update($validated);

        return $this->success(
            data: $jobPostingModel->fresh()->loadCount('applications'),
            code: SuccessCodeEnum::UPDATED,
        );
    }
}
