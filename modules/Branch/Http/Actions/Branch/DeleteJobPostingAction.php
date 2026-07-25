<?php

declare(strict_types=1);

namespace Modules\Branch\Http\Actions\Branch;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Core\Enums\SuccessCodeEnum;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\JobPosting\Models\JobPostingModel;

final class DeleteJobPostingAction extends BaseApiAction
{
    public function __invoke(Request $request, string $jobPosting): JsonResponse
    {
        $branch = $request->user('branch_api');

        $jobPostingModel = JobPostingModel::where('branch_id', $branch->id)->findOrFail($jobPosting);

        $jobPostingModel->delete();

        return $this->noContent(SuccessCodeEnum::DELETED);
    }
}
