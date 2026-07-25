<?php

declare(strict_types=1);

namespace Modules\JobPosting\Filament\Panels\Branch\Resources\JobPostingResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\JobPosting\Filament\Panels\Branch\Resources\JobPostingResource\JobPostingResource;

class EditJobPostingPage extends EditRecord
{
    protected static string $resource = JobPostingResource::class;
}
