<?php

declare(strict_types=1);

namespace Modules\JobPosting\Filament\Panels\Branch\Resources\JobPostingResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\JobPosting\Filament\Panels\Branch\Resources\JobPostingResource\JobPostingResource;

class CreateJobPostingPage extends CreateRecord
{
    protected static string $resource = JobPostingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['branch_id'] = filament()->auth()->id();

        return $data;
    }
}
