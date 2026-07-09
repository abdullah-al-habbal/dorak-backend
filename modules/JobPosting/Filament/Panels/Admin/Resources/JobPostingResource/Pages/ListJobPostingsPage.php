<?php
declare(strict_types=1);

namespace Modules\JobPosting\Filament\Panels\Admin\Resources\JobPostingResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\JobPosting\Filament\Panels\Admin\Resources\JobPostingResource\JobPostingResource;

class ListJobPostingsPage extends ListRecords
{
    protected static string $resource = JobPostingResource::class;
}
