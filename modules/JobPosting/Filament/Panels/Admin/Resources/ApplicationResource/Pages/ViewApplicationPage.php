<?php
declare(strict_types=1);

namespace Modules\JobPosting\Filament\Panels\Admin\Resources\ApplicationResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\JobPosting\Filament\Panels\Admin\Resources\ApplicationResource\ApplicationResource;

class ViewApplicationPage extends ViewRecord
{
    protected static string $resource = ApplicationResource::class;
}
