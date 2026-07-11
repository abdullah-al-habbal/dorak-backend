<?php

declare(strict_types=1);

namespace Modules\Branch\Filament\Panels\Admin\Resources\BranchResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Branch\Filament\Panels\Admin\Resources\BranchResource\BranchResource;

class CreateBranchPage extends CreateRecord
{
    protected static string $resource = BranchResource::class;
}
