<?php
declare(strict_types=1);

namespace Modules\Client\Filament\Panels\Admin\Resources\ClientResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Client\Filament\Panels\Admin\Resources\ClientResource\ClientResource;

class EditClientPage extends EditRecord
{
    protected static string $resource = ClientResource::class;
}
