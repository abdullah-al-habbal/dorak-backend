<?php

declare(strict_types=1);

namespace Modules\JobPosting\Eloquent\Resolvers\Barber;

use Modules\JobPosting\CQRS\Command\Barber\ApplyForJobCommand;
use Modules\JobPosting\Models\ApplicationModel;
use Modules\JobPosting\Models\JobPostingModel;
use Modules\JobPosting\ValuesObjects\ApplyForJobResult;

final class ApplyForJobEloquentResolver
{
    public function resolve(ApplyForJobCommand $command): ApplyForJobResult
    {
        $jobPosting = JobPostingModel::findOrFail($command->jobPostingId);

        if ($jobPosting->status !== 'open') {
            return ApplyForJobResult::notOpen();
        }

        $existing = ApplicationModel::where('job_posting_id', $jobPosting->id)
            ->where('barber_id', $command->barberId)
            ->exists();

        if ($existing) {
            return ApplyForJobResult::alreadyApplied();
        }

        $application = ApplicationModel::create([
            'job_posting_id' => $jobPosting->id,
            'barber_id' => $command->barberId,
            'profile_snapshot' => [
                'name' => $command->barberName,
                'email' => $command->barberEmail,
                'is_freelancer' => $command->isFreelancer,
            ],
            'status' => 'submitted',
        ]);

        return ApplyForJobResult::success($application);
    }
}
