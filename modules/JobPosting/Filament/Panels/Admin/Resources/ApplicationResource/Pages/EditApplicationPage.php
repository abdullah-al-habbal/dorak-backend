<?php
declare(strict_types=1);

namespace Modules\JobPosting\Filament\Panels\Admin\Resources\ApplicationResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\JobPosting\Filament\Panels\Admin\Resources\ApplicationResource\ApplicationResource;

class EditApplicationPage extends EditRecord
{
    protected static string $resource = ApplicationResource::class;
}
