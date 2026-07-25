<?php

declare(strict_types=1);

namespace Modules\JobPosting\Filament\Panels\Branch\Resources\JobPostingResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\JobPosting\Filament\Panels\Branch\Resources\JobPostingResource\JobPostingResource;

class ViewJobPostingPage extends ViewRecord
{
    protected static string $resource = JobPostingResource::class;
}
