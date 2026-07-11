<?php

declare(strict_types=1);

namespace Modules\JobPosting\Filament\Panels\Admin\Resources\ApplicationResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\JobPosting\Filament\Panels\Admin\Resources\ApplicationResource\ApplicationResource;

class ListApplicationsPage extends ListRecords
{
    protected static string $resource = ApplicationResource::class;
}
