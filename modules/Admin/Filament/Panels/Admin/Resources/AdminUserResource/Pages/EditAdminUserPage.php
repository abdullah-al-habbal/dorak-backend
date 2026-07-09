<?php
// modules/Admin/Filament/Panels/Admin/Resources/AdminUserResource/Pages/EditAdminUserPage.php
declare(strict_types=1);

namespace Modules\Admin\Filament\Panels\Admin\Resources\AdminUserResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Admin\Filament\Panels\Admin\Resources\AdminUserResource\AdminUserResource;

class EditAdminUserPage extends EditRecord
{
    protected static string $resource = AdminUserResource::class;
}
