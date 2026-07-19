<?php

declare(strict_types=1);

namespace Modules\JobPosting\Http\Actions\Barber;

use Illuminate\Http\JsonResponse;
use Modules\Core\Enums\ErrorCodeEnum;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\JobPosting\Http\Requests\Barber\ApplyForJobRequest;
use Modules\JobPosting\Http\Resources\Barber\ApplicationResource;
use Modules\JobPosting\Models\ApplicationModel;
use Modules\JobPosting\Models\JobPostingModel;

final class ApplyForJobAction extends BaseApiAction
{
    public function __invoke(ApplyForJobRequest $request, string $job): JsonResponse
    {
        $jobPosting = JobPostingModel::findOrFail($job);

        if ($jobPosting->status !== 'open') {
            return $this->businessError(ErrorCodeEnum::UNPROCESSABLE_ENTITY, message: __('Job posting is not open for applications'));
        }

        $existing = ApplicationModel::where('job_posting_id', $jobPosting->id)
            ->where('barber_id', $request->user()->id)
            ->exists();

        if ($existing) {
            return $this->businessError(ErrorCodeEnum::UNPROCESSABLE_ENTITY, message: __('Already applied to this job'));
        }

        $application = ApplicationModel::create([
            'job_posting_id' => $jobPosting->id,
            'barber_id' => $request->user()->id,
            'profile_snapshot' => [
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'is_freelancer' => $request->user()->is_freelancer,
            ],
            'status' => 'submitted',
        ]);

        return $this->created(
            data: new ApplicationResource($application),
            message: 'Application submitted successfully',
        );
    }
}
