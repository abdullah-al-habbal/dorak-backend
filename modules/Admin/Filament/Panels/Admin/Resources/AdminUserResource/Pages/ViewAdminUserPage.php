<?php
// modules/Admin/Filament/Panels/Admin/Resources/AdminUserResource/Pages/ViewAdminUserPage.php
declare(strict_types=1);

namespace Modules\Admin\Filament\Panels\Admin\Resources\AdminUserResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Admin\Filament\Panels\Admin\Resources\AdminUserResource\AdminUserResource;

class ViewAdminUserPage extends ViewRecord
{
    protected static string $resource = AdminUserResource::class;
}
