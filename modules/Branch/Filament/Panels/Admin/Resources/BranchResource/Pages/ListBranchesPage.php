<?php

declare(strict_types=1);

namespace Modules\Branch\Filament\Panels\Admin\Resources\BranchResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Branch\Filament\Panels\Admin\Resources\BranchResource\BranchResource;

class ListBranchesPage extends ListRecords
{
    protected static string $resource = BranchResource::class;
}
