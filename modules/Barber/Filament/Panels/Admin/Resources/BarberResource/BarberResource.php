<?php
// modules/Barber/Filament/Panels/Admin/Resources/BarberResource/BarberResource.php
declare(strict_types=1);

namespace Modules\Barber\Filament\Panels\Admin\Resources\BarberResource;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Activation\Filament\Actions\ToggleActivationAction;
use Modules\Barber\Models\BarberModel;

class BarberResource extends Resource
{
    protected static ?string $model = BarberModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $recordTitleAttribute = 'name.en';

    protected static ?string $slug = 'barbers';

    public static function form(Form $form): Form
    {
        return BarberFormSchema::make($form);
    }

    public static function table(Table $table): Table
    {
        return BarbersTable::make($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return BarberInfolistSchema::make($infolist);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBarbersPage::route('/'),
            'create' => Pages\CreateBarberPage::route('/create'),
            'view'   => Pages\ViewBarberPage::route('/{record}'),
            'edit'   => Pages\EditBarberPage::route('/{record}/edit'),
        ];
    }
}
