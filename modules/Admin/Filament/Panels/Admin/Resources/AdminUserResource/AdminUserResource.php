<?php
// modules/Admin/Filament/Panels/Admin/Resources/AdminUserResource/AdminUserResource.php
declare(strict_types=1);

namespace Modules\Admin\Filament\Panels\Admin\Resources\AdminUserResource;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Admin\Models\AdminModel;

class AdminUserResource extends Resource
{
    protected static ?string $model = AdminModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Admin Users';

    protected static ?string $pluralModelLabel = 'Admin Users';

    protected static ?string $slug = 'admin-users';

    public static function form(Form $form): Form
    {
        return AdminUserFormSchema::make($form);
    }

    public static function table(Table $table): Table
    {
        return AdminUsersTable::make($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return AdminUserInfolistSchema::make($infolist);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAdminUsersPage::route('/'),
            'create' => Pages\CreateAdminUserPage::route('/create'),
            'view'   => Pages\ViewAdminUserPage::route('/{record}'),
            'edit'   => Pages\EditAdminUserPage::route('/{record}/edit'),
        ];
    }
}
