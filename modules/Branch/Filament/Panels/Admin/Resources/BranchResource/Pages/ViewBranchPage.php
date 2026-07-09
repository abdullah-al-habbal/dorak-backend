<?php
declare(strict_types=1);

namespace Modules\Branch\Filament\Panels\Admin\Resources\BranchResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Branch\Filament\Panels\Admin\Resources\BranchResource\BranchResource;

class ViewBranchPage extends ViewRecord
{
    protected static string $resource = BranchResource::class;
}
