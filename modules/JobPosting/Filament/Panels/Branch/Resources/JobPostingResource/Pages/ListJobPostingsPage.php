<?php

declare(strict_types=1);

namespace Modules\JobPosting\Filament\Panels\Branch\Resources\JobPostingResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\JobPosting\Filament\Panels\Branch\Resources\JobPostingResource\JobPostingResource;

class ListJobPostingsPage extends ListRecords
{
    protected static string $resource = JobPostingResource::class;
}
