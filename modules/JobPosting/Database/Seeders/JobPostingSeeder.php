<?php

declare(strict_types=1);

namespace Modules\JobPosting\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Barber\Models\BarberModel;
use Modules\Branch\Models\BranchModel;
use Modules\JobPosting\Enums\ApplicationStatus;
use Modules\JobPosting\Enums\JobPostingStatusEnum;
use Modules\JobPosting\Models\ApplicationModel;
use Modules\JobPosting\Models\JobPostingModel;

class JobPostingSeeder extends Seeder
{
    public function run(): void
    {
        $branch = BranchModel::where('email', 'branch@dorak.sy')->first()
            ?? BranchModel::factory()->create(['email' => 'branch@dorak.sy']);

        $barber = BarberModel::where('email', 'barber@dorak.sy')->first()
            ?? BarberModel::factory()->create(['email' => 'barber@dorak.sy']);

        $job = JobPostingModel::create([
            'branch_id' => $branch->id,
            'title' => ['en' => 'Experienced Barber', 'ar' => 'حلاق ذو خبرة'],
            'description' => ['en' => 'Looking for an experienced barber', 'ar' => 'نبحث عن حلاق ذو خبرة'],
            'status' => JobPostingStatusEnum::Open->value,
        ]);

        ApplicationModel::create([
            'job_posting_id' => $job->id,
            'barber_id' => $barber->id,
            'profile_snapshot' => [
                'name' => $barber->getTranslation('name', 'en'),
                'bio' => 'Experienced barber looking for new opportunities',
                'is_freelancer' => $barber->is_freelancer,
                'rating' => 4.5,
            ],
            'status' => ApplicationStatus::Submitted->value,
        ]);
    }
}
