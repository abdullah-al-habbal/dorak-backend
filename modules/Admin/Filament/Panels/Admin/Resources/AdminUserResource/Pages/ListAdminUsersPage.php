<?php

// modules/Admin/Filament/Panels/Admin/Resources/AdminUserResource/Pages/ListAdminUsersPage.php
declare(strict_types=1);

namespace Modules\Admin\Filament\Panels\Admin\Resources\AdminUserResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Admin\Filament\Panels\Admin\Resources\AdminUserResource\AdminUserResource;

class ListAdminUsersPage extends ListRecords
{
    protected static string $resource = AdminUserResource::class;
}
