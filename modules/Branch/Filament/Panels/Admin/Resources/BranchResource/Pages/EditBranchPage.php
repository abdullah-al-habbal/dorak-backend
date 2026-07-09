<?php
declare(strict_types=1);

namespace Modules\Branch\Filament\Panels\Admin\Resources\BranchResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Branch\Filament\Panels\Admin\Resources\BranchResource\BranchResource;

class EditBranchPage extends EditRecord
{
    protected static string $resource = BranchResource::class;
}
