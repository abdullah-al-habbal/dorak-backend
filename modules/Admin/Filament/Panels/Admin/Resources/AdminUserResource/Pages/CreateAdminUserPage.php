<?php
// modules/Admin/Filament/Panels/Admin/Resources/AdminUserResource/Pages/CreateAdminUserPage.php
declare(strict_types=1);

namespace Modules\Admin\Filament\Panels\Admin\Resources\AdminUserResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Admin\Filament\Panels\Admin\Resources\AdminUserResource\AdminUserResource;

class CreateAdminUserPage extends CreateRecord
{
    protected static string $resource = AdminUserResource::class;
}
