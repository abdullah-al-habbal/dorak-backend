<?php

declare(strict_types=1);

namespace Modules\JobPosting\Filament\Panels\Admin\Resources\JobPostingResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\JobPosting\Filament\Panels\Admin\Resources\JobPostingResource\JobPostingResource;

class CreateJobPostingPage extends CreateRecord
{
    protected static string $resource = JobPostingResource::class;
}
